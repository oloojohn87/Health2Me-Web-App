import java.io.*;
import java.net.*;
import java.sql.*;
import java.text.SimpleDateFormat;
import java.util.Calendar;


public class AudioParsingZennith {

	
	
	public static void main(String[] args) throws InstantiationException, IllegalAccessException, ClassNotFoundException, IOException, InterruptedException 
	{
		//String url = "jdbc:mysql://dev.health2.me/";
        //String dbName = "monimed";
        //String userName = "monimed"; 
        //String password = "ardiLLA98";
        
		InetAddress iAddress = InetAddress.getLocalHost();
		String canonicalHostName = iAddress.getCanonicalHostName();
		String fname;
		System.out.println(canonicalHostName);
    	if(canonicalHostName.equals("10.112.65.124"))
		{
			fname="Database_dev.txt";
		}
		else if(canonicalHostName.equals("10.12.83.120"))
		{
			fname="Database_prod.txt";
		}
		else
		{
			writeLog("Cannot Identify Server");
			return;
		}
		System.out.println(fname);
		BufferedReader br = new BufferedReader(new FileReader("..//environment_details//"+fname));
		String line1;
		String content="";
		while ((line1 = br.readLine()) != null) 
		{
			content = content + line1;
		}
		br.close();
		
		String [] env = content.split(";");
		String url = "jdbc:mysql://"+(env[0].split("="))[1].replaceAll("\"","") + "/";
    	String dbName = (env[1].split("="))[1].replaceAll("\"","");
		String userName = (env[2].split("="))[1].replaceAll("\"",""); 
        String password = (env[3].split("="))[1].replaceAll("\"","");
		
		
		
		
		
		
		long timeout = 300000; //5 minutes
        
        writeLog("Service started executing ");
        try
        {
			String enc_query = "select pass from encryption_pass where id = (select max(id) from encryption_pass)";
			String query ="select l.idpin,l.rawimage,l.creatortype,l.idcreator from pending_audio p,lifepin l where l.idpin = p.idpin and l.vs=0 order by l.idpin";
        	Class.forName("com.mysql.jdbc.Driver").newInstance(); 
        	Connection conn = DriverManager.getConnection(url+dbName,userName,password);
        	
			Statement stmte = conn.createStatement();
			ResultSet rse = stmte.executeQuery(enc_query);
			rse.next();
			String enc_pass = rse.getString(1);
			
				System.out.println("Encryption Password used is "+enc_pass);
			
			Statement stmt = conn.createStatement();
        	ResultSet rs = stmt.executeQuery(query);
        	while(rs.next())
        	{
        		int idp=rs.getInt(1);
        		String file_name = rs.getString(2);
        		int creator_type = rs.getInt(3);
        		int creator_id = rs.getInt(4);
				//Absolute path mentioned here. It different in production and developement environment
				File f = new File("c:/xampp/htdocs/Packages/"+file_name);
        	   // File f = new File("c:/xampp/htdocs/Packages_Encrypted/"+file_name);
        		if(file_name==null || f.exists()==false)
   				{
   					writeLog("File with "+idp+" not found");
   					
					Statement stmt3 = conn.createStatement();
					stmt3.executeUpdate("update lifepin set a_s=1 where idpin="+idp);
					
					Statement stmt1 = conn.createStatement();
            		stmt1.executeUpdate("delete from pending_audio where idpin = " + idp);
					
            		Statement stmt2 = conn.createStatement();
					stmt2.executeUpdate("insert into processed_status values("+idp+",'File not Found')");
					
					
					continue;
   				}
        		
        		writeLog("Job with ID "+idp+" started ");
			    
			    String extension = "";
				String fstrname="";
			    int i = file_name.lastIndexOf('.');
			    if (i > 0) 
			    {
			        extension = file_name.substring(i+1);
					fstrname=file_name.substring(0,i);
			    }
        		
				writeLog("Filename extracted "+file_name);
				
				writeLog(" New mp3 filename extracted "+fstrname);
				//ProcessAudioFile pa=new ProcessAudioFile();
				//String generated_text=pa.processfile("c:/xampp/htdocs/Packages/"+file_name,"audio/"+fstrname+".flac");
				
				Runtime.getRuntime().exec("c:/xampp/htdocs/ffmpeg/bin/ffmpeg -i c:/xampp/htdocs/Packages/"+file_name+" -f mp3 audio/"+fstrname+".mp3");
				
			    String command;
			    //											Generate Text from Input File
			
				//System.out.println(generated_text);
				
				//Statement stmt1 = conn.createStatement();
        		//stmt1.executeUpdate("update lifepin set textorel='"+ generated_text +"' where idpin = " + idp);
				
				writeLog("     Extracted speech to text written to database ");
				
				//Audio2Pdf a2p=new Audio2Pdf();
				// a2p.createpdf(generated_text, "c:/xampp/htdocs/Packages/"+fstrname+".pdf");
				
				connectZennith putfile=new connectZennith();
				
				putfile.putAudio("audio/"+fstrname+".mp3",fstrname+".mp3");
				
				Statement stmt8 = conn.createStatement();
				stmt8.executeUpdate("update lifepin set a_s=1 where idpin="+idp);
				writeLog("     Set A_S=1 for the Job");
				
    		    Statement stmt6 = conn.createStatement();
        		stmt6.executeUpdate("delete from pending_audio where idpin = " + idp);
        		writeLog("     Pending Table Entry Deleted");
        		
        		Statement stmt7 = conn.createStatement();
				stmt7.executeUpdate("insert into processed_status values("+idp+",'Success')");
				writeLog("     Status inserted into Processed_Status Table");
				
				
				
				//-------------------------------------------------------------------------------------------------------------------------------
        	}//end rs.next()
        }//end try
        catch (SQLException ex) 
        {
            	writeLog("Error connecting to database");
            	ex.printStackTrace();
        }
		
        writeLog("Service Exiting ");
		System.exit(0);
	}//end main
	
	
	
	
	static void writeLog(String s)
	{
		try 
		{
			String fdate = new SimpleDateFormat("yyyyMMdd").format(Calendar.getInstance().getTime());
		    PrintWriter out = new PrintWriter(new BufferedWriter(new FileWriter("Logs/Parse_audio_log_"+fdate, true)));
		    String timestamp = new SimpleDateFormat("yyyyMMdd_HHmmss").format(Calendar.getInstance().getTime());
			out.println(s + " at "+timestamp);
		    out.close();
		} catch (IOException e) {
		    System.out.println("Error writing to Log");
		}
	
	}
	
	
}
