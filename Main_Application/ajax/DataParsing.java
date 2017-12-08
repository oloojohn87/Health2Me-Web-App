import java.io.BufferedReader;
import java.io.BufferedWriter;
import java.io.FileWriter;
import java.io.IOException;
import java.io.InputStreamReader;
import java.io.PrintWriter;
import java.net.URL;
import java.net.URLConnection;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;
import java.text.SimpleDateFormat;
import java.util.Calendar;



public class DataParsing{

    public static void main(String args[]) throws InterruptedException, ClassNotFoundException, InstantiationException, IllegalAccessException, IOException {
    	String url = "jdbc:mysql://dev.health2.me/";
        String dbName = "monimed";
        String userName = "monimed"; 
        String password = "ardiLLA98";
       
        try {
		    PrintWriter out = new PrintWriter(new BufferedWriter(new FileWriter("Parse_log", true)));
		    String timeStamp = new SimpleDateFormat("yyyyMMdd_HHmmss").format(Calendar.getInstance().getTime());
		    out.println("Service invoked at "+timeStamp);
		    out.close();
		} catch (IOException e) {
		    System.out.println("Error writing to Log");
		}
       
        String query ="select idpin,rawimage from pending";
       
        
        //while(true)
        //{
        	try 
        	{
        		//getting database connection to MySQL server
        		Class.forName("com.mysql.jdbc.Driver").newInstance(); 
        		Connection conn = DriverManager.getConnection(url+dbName,userName,password);
              	Statement stmt = conn.createStatement();
        		ResultSet rs = stmt.executeQuery(query);
        		//System.out.println("Here");
        		while(rs.next())
        		{
        			int idp = rs.getInt(1);
        			String file_name = rs.getString(2); 
        			System.out.println(idp + "   " + file_name);
					
					try {
        			    PrintWriter out = new PrintWriter(new BufferedWriter(new FileWriter("Parse_log", true)));
        			    String timeStamp = new SimpleDateFormat("yyyyMMdd_HHmmss").format(Calendar.getInstance().getTime());
        			    out.println("Job with ID "+idp+" started at "+timeStamp);
        			    out.close();
        			} catch (IOException e) {
        			    System.out.println("Error writing to Log");
        			}
					
					
					
					System.out.println("Processing batch file");
        			Process p = Runtime.getRuntime().exec("c:/xampp/htdocs/ExtractText.bat " + file_name);
        		    p.waitFor();
        		    
        		    BufferedReader reader = new BufferedReader(new InputStreamReader(p.getInputStream()));
        		    String line = reader.readLine();
        		    if(line.equals("failure")==true)
        		    {
        		    	try {
            			    PrintWriter out = new PrintWriter(new BufferedWriter(new FileWriter("Parse_log", true)));
            			    String timeStamp = new SimpleDateFormat("yyyyMMdd_HHmmss").format(Calendar.getInstance().getTime());
            			    out.println("Script returned Error for Job ID " + idp);
            			    out.close();
            			    
            			    System.out.println("Deleting entry for "+idp);
                		    Statement stmt1 = conn.createStatement();
                    		stmt1.executeUpdate("delete from pending where idpin = " + idp);
							Statement stmt2 = conn.createStatement();
							stmt2.executeUpdate("insert into processed_status values("+idp+",'Fail')");
            			} catch (IOException e) {
            			    System.out.println("Error writing to Log");
            			}
        		    	continue;
        		    	
        		    }
        		    		
        		    		
        		    System.out.println("Finished processing batch file");
        		    URL link = new URL("http://dev.health2.me/parsev3.php?name="+file_name+"&idp="+idp);
        		    //URL yahoo = new URL("http://www.yahoo.com/");
        	        
        		    URLConnection yc = link.openConnection();
        	        BufferedReader in = new BufferedReader(
        	                                new InputStreamReader(
        	                                yc.getInputStream()));
        	        String inputLine;

        	        while ((inputLine = in.readLine()) != null) 
        	            System.out.println(inputLine);
        	        in.close();
        		    
        		    System.out.println(file_name + "Serviced");
        		    System.out.println("Deleting entry for "+file_name);
        		    Statement stmt1 = conn.createStatement();
            		stmt1.executeUpdate("delete from pending where idpin = " + idp);
					
					Statement stmt3 = conn.createStatement();
            		stmt3.executeUpdate("update lifepin set a_s=1 where  idpin = " + idp);
					
					Statement stmt2 = conn.createStatement();
					stmt2.executeUpdate("insert into  processed_status values("+idp+",'Success')");
            		
        			try {
        			    PrintWriter out = new PrintWriter(new BufferedWriter(new FileWriter("Parse_log", true)));
        			    String timeStamp = new SimpleDateFormat("yyyyMMdd_HHmmss").format(Calendar.getInstance().getTime());
        			    out.println("Job with ID "+idp+" Processed at "+timeStamp);
        			    out.close();
        			} catch (IOException e) {
        			    System.out.println("Error writing to Log");
        			}
                }
        		rs.close();
				
        		
        	}     
            catch (SQLException ex) 
            {
            	System.out.println("In exception");
            	ex.printStackTrace();
            } 
        //}
				try {
        			    PrintWriter out = new PrintWriter(new BufferedWriter(new FileWriter("Parse_log", true)));
        			    
        			    out.println("Exiting service");
        			    out.close();
        			} catch (IOException e) {
        			    System.out.println("Error writing to Log");
        			}
    }  
	
   
}