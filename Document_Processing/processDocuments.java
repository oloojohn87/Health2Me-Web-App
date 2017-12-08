import java.io.*;
import java.net.*;
import java.sql.*;
import java.text.SimpleDateFormat;
import java.util.Calendar;
import org.json.simple.JSONArray;
import org.json.simple.JSONObject;
import org.json.simple.parser.JSONParser;
import org.json.simple.parser.ParseException;
import org.json.simple.*;


public class processDocuments {

	
	
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
			//return;
		}
		
		//System.out.println(fname);
		
		//String url = "jdbc:mysql://beta.health2.me/";
		String url = "jdbc:mysql://dev.health2.me/";
        String dbName = "monimed";
        String userName = "monimed"; 
        String password = "ardiLLA98";

        String input="";
        String output="";
        String cmd="";
        String pckPath="";
        String cmd2="";

        JSONArray ext=null;

		try {

			JSONParser parser = new JSONParser();

			JSONObject a = (JSONObject)parser.parse(new FileReader("fields.json"));

		  	JSONObject path=(JSONObject)a.get("path");

		  	JSONObject command=(JSONObject)a.get("command");


		  	ext=(JSONArray)a.get("extension");

		  	input=(path.get("input")).toString();
		  	output=(path.get("output")).toString();
		  	pckPath=(path.get("home")).toString();


		  	System.out.println(ext);

		  	cmd=(command.get("convertDocToPdf")).toString();

		  	cmd2=(command.get("convertPDFToPng")).toString();




		}catch(Exception ex){
			writeLog("Error reading the input fields from fields.json");
            ex.printStackTrace();

		}

	
		
		long timeout = 300000; //5 minutes
        
        writeLog("Service started executing ");

        try
        {
			String enc_query = "select pass from encryption_pass where id = (select max(id) from encryption_pass)";
			String query ="select l.idpin,p.rawimage,l.creatortype,l.idcreator from pending_documents p,lifepin l where l.idpin = p.idpin order by l.idpin";
        	Class.forName("com.mysql.jdbc.Driver").newInstance(); 
        	Connection conn = DriverManager.getConnection(url+dbName,userName,password);
        	
			Statement stmte = conn.createStatement();
			ResultSet rse = stmte.executeQuery(enc_query);
			rse.next();
			String enc_pass = rse.getString(1);
			
				System.out.println("Encryption Password used is "+enc_pass);
			
				

			Statement stmt = conn.createStatement();
        	ResultSet rs = stmt.executeQuery(query);

        	processPDFDoc.conn=conn;
        	processPDFDoc.enc_pass=enc_pass;
        	processPDFDoc.command=cmd2;


        	//processPDFDoc.processDocuments(12345,"eML05707f2b1c5e881378a3e7799165150dc.pdf","/home/ITGroup/testdocfiles/New/pdf/","/home/ITGroup/testdocfiles/New/");

        	//System.exit(0);	//comment this code. Added just for testing purpose

        	while(rs.next())
        	{
        		int idp=rs.getInt(1);
        		String file_name = rs.getString(2);
        		int creator_type = rs.getInt(3);
        		int creator_id = rs.getInt(4);
                File f = new File(input+file_name);
                
        	    String file_path = "";
        		if(file_name==null || f.exists()==false)
   				{
   					writeLog("File with "+idp+" not found");
   					
					/*Statement stmt3 = conn.createStatement();
					stmt3.executeUpdate("update lifepin set a_s=1 where idpin="+idp);*/
                    
					Statement stmt1 = conn.createStatement();
            		stmt1.executeUpdate("delete from pending_documents where idpin = " + idp);
					
            		Statement stmt2 = conn.createStatement();
					stmt2.executeUpdate("insert into processed_documents values("+idp+",'File not Found')");

					continue;
   				}
        		
        		writeLog("Job with ID "+idp+" started ");
			    
			    String extension = "";

			    int i = file_name.lastIndexOf('.');
			    if (i > 0) 
			    {
			        extension = file_name.substring(i+1);
			    }
        		
			    String command="0";
			    //											verify whether the file extension is supported and process it further
			    //-------------------------------------------------------------------------------------------------------------------------------

			    ProcessBuilder pb=null;
			    for (Object ex : ext ){

			    		String ex_t=ex.toString();
			    		if(extension.equalsIgnoreCase(ex_t)){
			    			command = cmd;
			    			 pb= new ProcessBuilder(command,f.getAbsolutePath(),output);
			    			System.out.println("val"+ex_t);
			    			break;
			    		}

			    }

   				if(pb!=null) {

				    Process p = pb.start();
	                //Runtime.getRuntime().exec(command);
				    Worker worker = new Worker(p);
					writeLog("     Starting Thread");
				    worker.start();
				    try 
				    {
				        worker.join(timeout);
				        if (worker.exit != null)
						{
				        	writeLog("     Thread finished execution ");
				        }
						else
						{
							writeLog("     Thread timed out");
							//Process p1 = Runtime.getRuntime().exec("taskkill  /IM tesseract.exe /F");
							//p1.waitFor();
							//p.destroy();
							
				        }
				    } catch(InterruptedException ex) 
				    {
				        worker.interrupt();
				        Thread.currentThread().interrupt();
				        //throw ex;
				    } //finally 
				    
				        p.destroy();
				 
				    
	    		    if(worker.status.equals("failure")==true)
	    		    {
	    		    	writeLog("Script returned Error for Job ID " + idp);
	    		    	
	    		    	/*Statement stmt3 = conn.createStatement();
						stmt3.executeUpdate("update lifepin set a_s=1 where idpin="+idp);*/
						
						Statement stmt1 = conn.createStatement();
	            		stmt1.executeUpdate("delete from pending_documents where idpin = " + idp);
						
	            		Statement stmt2 = conn.createStatement();
						stmt2.executeUpdate("insert into processed_documents values("+idp+",'Script returned Error')");
						
						
						continue;
	    		    }
				    
				    
	    		    Statement stmt6 = conn.createStatement();
	        		stmt6.executeUpdate("delete from pending_documents where idpin = " + idp);
	        		writeLog("     Pending_documents Table Entry Deleted");
	        		
	        		Statement stmt7 = conn.createStatement();
					stmt7.executeUpdate("insert into processed_documents values("+idp+",'Success')");
					writeLog("     Status inserted into Processed_documents Table");

					processPDFDoc.processDocuments(idp,file_name,output,pckPath);
					
				}else{	
					writeLog("Document extension not supported for Job ID " + idp);
			    	continue;
			    }
				
				//-------------------------------------------------------------------------------------------------------------------------------
        	}//end rs.next()
        }//end try
        catch (Exception ex) 
        {
            	writeLog("Error connecting to database");
            	ex.printStackTrace();
        }
		
        writeLog("Service Exiting ");
		System.exit(0);
	}//end main
	

	
	
	public static void writeLog(String s)
	{
		try 
		{
			String fdate = new SimpleDateFormat("yyyyMMdd").format(Calendar.getInstance().getTime());
		    PrintWriter out = new PrintWriter(new BufferedWriter(new FileWriter("Logs/Parse_log_"+fdate, true)));
		    String timestamp = new SimpleDateFormat("yyyyMMdd_HHmmss").format(Calendar.getInstance().getTime());
			out.println(s + " at "+timestamp);
		    out.close();
		} catch (IOException e) {
		    System.out.println("Error writing to Log");
		}
	
	}
	
	

}

class Worker extends Thread 
{
	  private final Process process;
	  Integer exit;
	  String status;
	  Worker(Process process) 
	  {
	    this.process = process;
	    this.status="failure";
	  }
	  public void run() 
	  {
	    try 
	    { 
	      exit = process.waitFor();
		  BufferedReader reader = new BufferedReader(new InputStreamReader(process.getInputStream()));
          String line = reader.readLine();
          if(line.equals("failure")==true)
		  {
			status="failure";
		  }
		  else
		  {
			status="success";

		  }
	    } 
	    catch (InterruptedException | IOException ignore) 
	    {
	      status="failure";
	      return;
	    }
	  }  
}
