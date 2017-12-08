import java.io.*;
import java.net.*;
import java.sql.*;
import java.text.SimpleDateFormat;
import java.util.Calendar;


public class Health2me {

	
	
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
			String query ="select l.idpin,l.rawimage,l.creatortype,l.idcreator from pending p,lifepin l where l.idpin = p.idpin and l.vs=0 order by l.idpin";
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
        	    File f = new File("c:/xampp/htdocs/Packages_Encrypted/"+file_name);
        		if(file_name==null || f.exists()==false)
   				{
   					writeLog("File with "+idp+" not found");
   					
					Statement stmt3 = conn.createStatement();
					stmt3.executeUpdate("update lifepin set a_s=1 where idpin="+idp);
					
					Statement stmt1 = conn.createStatement();
            		stmt1.executeUpdate("delete from pending where idpin = " + idp);
					
            		Statement stmt2 = conn.createStatement();
					stmt2.executeUpdate("insert into processed_status values("+idp+",'File not Found')");
					
					
					continue;
   				}
        		
        		writeLog("Job with ID "+idp+" started ");
			    
			    String extension = "";
			    int i = file_name.lastIndexOf('.');
			    if (i > 0) 
			    {
			        extension = file_name.substring(i+1);
			    }
        		
			    String command;
			    //											Generate Text from Input File
			    //-------------------------------------------------------------------------------------------------------------------------------
			    if(extension.equals("pdf") || extension.equals("PDF"))
			    {
			    	//Execute the batch file to Generate text from PDF
    			    command = "ExtractText.bat " + file_name + " " + enc_pass;  
        		    
			    }
			    else
			    {
			    	//Extract text from JPG file
			    	command = "ExtractText_JPG.bat " + file_name + " " + enc_pass;
        		    
			    	
			    }
			    
			    
			    
			    Process p = Runtime.getRuntime().exec(command);
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
						Process p1 = Runtime.getRuntime().exec("taskkill  /IM tesseract.exe /F");
						p1.waitFor();
						//p.destroy();
						
			        }
			    } catch(InterruptedException ex) 
			    {
			        worker.interrupt();
			        Thread.currentThread().interrupt();
			        //throw ex;
			    } //finally 
			    //{
					
			        p.destroy();
			    //}
			    
			    
			    
    		    //BufferedReader reader = new BufferedReader(new InputStreamReader(p.getInputStream()));
    		    //String line = reader.readLine();
    		    if(worker.status.equals("failure")==true)
    		    {
    		    	writeLog("Script returned Error for Job ID " + idp);
    		    	
    		    	Statement stmt3 = conn.createStatement();
					stmt3.executeUpdate("update lifepin set a_s=1 where idpin="+idp);
					
					Statement stmt1 = conn.createStatement();
            		stmt1.executeUpdate("delete from pending where idpin = " + idp);
					
            		Statement stmt2 = conn.createStatement();
					stmt2.executeUpdate("insert into processed_status values("+idp+",'Script returned Error')");
					
					
					continue;
    		    }
			    
			    
			    
			    
				
				writeLog("     Data Extraction finished ");
				//-------------------------------------------------------------------------------------------------------------------------------
				String generated_text = clean_text();
				//System.out.println(generated_text);
				
				Statement stmt1 = conn.createStatement();
        		stmt1.executeUpdate("update lifepin set textorel='"+ generated_text +"' where idpin = " + idp);
				
				writeLog("     Extracted Data written to database ");
				
				
				//                              Find IDS
				//-------------------------------------------------------------------------------------------------------------------------------
				int sug_id;
				if(creator_type==1)
				{	
					String q = "select name,surname,identif from usuarios where identif in (select distinct(u.idus) from doctorslinkusers u,lifepin l where u.idmed=l.idcreator and l.idcreator="+creator_id+")";
					Statement stmt4 = conn.createStatement();
					ResultSet rs1 = stmt4.executeQuery(q);
					rs1.last();
					int count = rs1.getRow();
					rs1.beforeFirst();

	        	
					int [] arr = new int[count];
					int [] ids = new int[count];
	        	
					String [] words = generated_text.split(" ");
					int j=0;	        	
					while(rs1.next())
					{
						String name = rs1.getString(1);
						String surname = rs1.getString(2);
						int identif = rs1.getInt(3);
						ids[j] = identif;
						for(int tok=0;tok<words.length;tok++)
						{
						
							if(words[tok].matches(".*[a-zA-Z]+.*"))
							{
								int dist = editDistance(name.toLowerCase(),words[tok].toLowerCase());
								//System.out.println("Comparing "+name+" and "+words[tok]+" -- "+dist);
								if(dist <= 2 && dist < Math.min(name.length(),words[tok].length()))
								{
									if(dist==0)
									arr[j]=arr[j]+10;
									//System.out.print("***");
									arr[j]++;
								}
							
								dist = editDistance(surname.toLowerCase(),words[tok].toLowerCase());
								//System.out.println("Comparing "+surname+" and "+words[tok]+" -- "+dist);
								if(dist <= 2 && dist < Math.min(surname.length(),words[tok].length()))
								{
									if(dist==0)
									arr[j]=arr[j]+10;
									//System.out.print("***");
									arr[j]++;
								}
							}
						
						}
	        		
       		       		j++;
					}//end rs1.next()
				
					int max=-1;
					int pos=-1;
					for(int k=0;k<j;k++)
					{
						if(arr[k]>max && arr[k]>0)
						{
							max = arr[k];
							pos = k;
						}
						System.out.println(ids[k] +" --> "+arr[k]);
					}
					
					if(pos!=-1)
					{
						sug_id=ids[pos];
						writeLog("     Auto-Parsing suggested ID "+sug_id );
					}
					else
					{
						sug_id=0;
						writeLog("     Auto-Parsing did not find any ID ");
					}
								
					
				}//end if creator is a doctor
				else
				{
					if(creator_id>0)
					{
						sug_id=creator_id;
						writeLog("     Auto-Parsing suggested ID "+sug_id);
					}
					else
					{
						sug_id=0;
						writeLog("     Auto-Parsing did not find any ID ");
					}
				}
				Statement stmt5 = conn.createStatement();
				stmt5.executeUpdate("update lifepin set suggestedid ="+sug_id+" where idpin="+idp);	
				writeLog("     Updated column suggestedid in Lifepin ");
				//-------------------------------------------------------------------------------------------------------------------------------
				//										Suggesting Dates								
				//-------------------------------------------------------------------------------------------------------------------------------
				URL link = new URL("http://dev.health2.me/Data_Parsing/parsev4.php?&idp="+idp);
    		    URLConnection yc = link.openConnection();
				
				writeLog("     Control returned from Date Parsing ");
				
				Statement stmt8 = conn.createStatement();
				stmt8.executeUpdate("update lifepin set a_s=1 where idpin="+idp);
				writeLog("     Set A_S=1 for the Job");
				
    		    Statement stmt6 = conn.createStatement();
        		stmt6.executeUpdate("delete from pending where idpin = " + idp);
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
	
	
	static String clean_text() throws IOException
	{
		//FileInputStream fstream = new FileInputStream("c:/xampp/htdocs/ExtractedData.txt");
		FileInputStream fstream = new FileInputStream("ExtractedData.txt");
	    DataInputStream in = new DataInputStream(fstream);
	    BufferedReader br = new BufferedReader(new InputStreamReader(in));

		String strLine;
		String textorel ="";
	    //Read File Line By Line
	    while ((strLine = br.readLine()) != null)   
	    {
	    		strLine = strLine.replace("\"", " ");
				strLine = strLine.replace("'", " ");
				System.out.println(strLine);
				textorel=textorel + " " + strLine;
	    }
		in.close();
		return textorel;

	}
	
	
	static void writeLog(String s)
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
	
	public static int editDistance(String s, String t){
	    int m=s.length();
	    int n=t.length();
	    int[][]d=new int[m+1][n+1];
	    for(int i=0;i<=m;i++){
	      d[i][0]=i;
	    }
	    for(int j=0;j<=n;j++){
	      d[0][j]=j;
	    }
	    for(int j=1;j<=n;j++){
	      for(int i=1;i<=m;i++){
	        if(s.charAt(i-1)==t.charAt(j-1)){
	          d[i][j]=d[i-1][j-1];
	        }
	        else{
	          d[i][j]=min((d[i-1][j]+1),(d[i][j-1]+1),(d[i-1][j-1]+1));
	        }
	      }
	    }
	    return(d[m][n]);
	  }
	  
	  public static int min(int a,int b,int c){
	    return(Math.min(Math.min(a,b),c));
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
