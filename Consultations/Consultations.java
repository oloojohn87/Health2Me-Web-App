import java.io.*;
import java.net.*;
import java.sql.*;
import java.text.SimpleDateFormat;
import java.util.Calendar;


public class Consultations {

	/**
	 * @param args
	 * @throws IOException 
	 */
	public static void main(String[] args) throws IOException {
		// TODO Auto-generated method stub
		//String url = "jdbc:mysql://dev.health2.me/";
        //String dbName = "monimed";
        //String userName = "monimed"; 
        //String password = "ardiLLA98";
        
		InetAddress iAddress = InetAddress.getLocalHost();
		String canonicalHostName = iAddress.getCanonicalHostName();
        String hostName = iAddress.getHostName();
		String fname;
		System.out.println(hostName);
    	if(canonicalHostName.equals("10.112.65.124"))
		{
			fname="Database_dev.txt";
		}
		else if(canonicalHostName.equals("10.12.83.120"))
		{
			fname="Database_prod.txt";
		}
        else if(canonicalHostName.equals("ip-10-179-161-178.ec2.internal"))
        {
            fname="Database_beta.txt";
        }
		else
		{
            System.out.println("Cannot Identify Server");
			return;
		}
		System.out.println(fname);
		BufferedReader br = new BufferedReader(new FileReader("/var/www/vhost1/environment_details/"+fname));
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
		
		
        try
        {
        	String query = "SELECT consultationId,Doctor FROM consults WHERE Status = 'In Progress' AND Type = 'video' AND TIMESTAMPDIFF(SECOND,lastActive,now()) > 59";
        	
        	Class.forName("com.mysql.jdbc.Driver").newInstance(); 
        	Connection conn = DriverManager.getConnection(url+dbName,userName,password);
        	Statement stmt = conn.createStatement();
        	ResultSet rs = stmt.executeQuery(query);
        	//System.out.println("Here");
        	while(rs.next())
        	{
        		int con_id = rs.getInt(1);
        		int doc_id = rs.getInt(2);
                
        		String update_consultation_query = "UPDATE consults SET Status = 'Failed', Length = TIMESTAMPDIFF(SECOND,DateTime,now()) WHERE consultationId = "+con_id;
        		Statement stmt_update_consultation = conn.createStatement();
        		stmt_update_consultation.executeUpdate(update_consultation_query);
        		
        		String update_doctor_query = "UPDATE doctors SET in_consultation = 0 WHERE id = "+doc_id;
        		Statement stmt_update_doctor = conn.createStatement();
        		stmt_update_doctor.executeUpdate(update_doctor_query);
        	
        		
        		
        	}
        	
        	
        	
        	
        	
        }
        catch(Exception e)
        {
			e.printStackTrace();
        	System.out.println("Database Problem");
        }
        
        
        
        
        
        
		

	}
}
