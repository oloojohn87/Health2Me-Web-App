import java.util.*;
import java.io.*;
import java.net.*;
import java.sql.*;

public class processPDFDoc {


	public static Connection conn=null;
	static Statement stmt=null;
	static ResultSet rs=null;
	public static String enc_pass="";
	public static String command="";

	String cadenaConvert="";
	
	public static int processDocuments(int IdPin,String file_name,String filepath,String dir) throws Exception
	{



 						String locFile = dir+"Packages_Encrypted/";
						String locFileTH = dir+"PackagesTH_Encrypted/"; 

						String name=file_name.substring(0,file_name.lastIndexOf("."));
					
						String extension="pdf";
						String extensionTH="png";
						//String new_image_nameTH = 'eML'.$confcode.'.png';	

						String new_image_name=name+"."+extension;
						String new_image_nameTH=name+"."+extensionTH;
						

						//cadenaConvert = "convert "+locFile+"/"+new_image_name+" -colorspace RGB -geometry 200"+locFileTH+"/"+new_image_nameTH+" 2>&1";
						processDocuments.writeLog("-------converting PDF to PNG-------");
						System.out.println("The encyption password "+enc_pass);

						List<String> cmds = new ArrayList<String>();
						
						//The below list are start contains .sh file to be exescuted and corresponding arguments
						//All are dynamically set based on the pdf file path location
                        cmds.add(command);
				        cmds.add(filepath+new_image_name);	//path for the pdf file created from docx file
				        cmds.add(locFileTH+new_image_nameTH);	//path of the png file created from pdf file
				        cmds.add(enc_pass);	//encryption password for the open ssl encryption
				        cmds.add(dir+"temp/"+new_image_nameTH);	//temp directory
				        cmds.add(dir+"temp/"+new_image_name);
				        cmds.add(locFile+new_image_name);	//path for the pdf file 

                        ProcessBuilder pb=new ProcessBuilder(cmds);

                        //This below code executed the processbuilder into a separate worker thread
						    		
                        if(pb!=null) {

							    Process p = pb.start();
				                //Runtime.getRuntime().exec(command);
							    Worker worker = new Worker(p);
								System.out.println("     Starting Thread");
							    worker.start();
							    try 
							    {
							        worker.join(300000);
							        if (worker.exit != null)
									{
							        	System.out.println("     Thread finished execution ");
							        	processDocuments.writeLog("-------Thread PDF to PNG complete-------");

							        }
									else
									{
										System.out.println("   Thread timed out");
										//Process p1 = Runtime.getRuntime().exec("taskkill  /IM tesseract.exe /F");
										//p1.waitFor();
										//p.destroy();
										
							        }
							    } catch(InterruptedException ex) 
							    {
							        worker.interrupt();
							        Thread.currentThread().interrupt();
							        processDocuments.writeLog("-------Exception PDF to PNG converstion-------");
							        processDocuments.writeLog(""+ ex.getMessage());
							        //throw ex;
							    } //finally 
							    
							        p.destroy();
						}
					    

                       processDocuments.writeLog("-------completed PDF to PNG converstion-------");
						//Commented the below part for testing purpose
					  
                        if(conn!=null){
                        	
                        	stmt=conn.createStatement();

                        	String query="UPDATE lifepin SET IdEmail='1', RawImage='"+new_image_name+"' , FechaInput=NOW(), ValidationStatus=8 ,fs=1 WHERE IdPin="+IdPin;
                        	stmt.execute(query);

							// if successfully insert data into database, displays message "Successful". 
							processDocuments.writeLog("-------DocToPDF Process complete. Updated the lifepin Idpin "+ IdPin +"-------");

                        } else{

                        		processDocuments.writeLog("-------Sql connection error-------");

                        }
					
							     
                        return 1;

	}


	/*public static void setPasswordEncryption(String enc_pass){
		this.enc_pass=enc_pass;
	}
	public static void setDatabase(Connection conn) {
		this.conn=conn;
	}*/


    private static String loadStream(InputStream s) throws Exception {
        BufferedReader br = new BufferedReader(new InputStreamReader(s));
        StringBuilder sb = new StringBuilder();
        String line;
        while ((line = br.readLine()) != null)
            sb.append(line).append("\n");
        return sb.toString();
    }

	public static void main(String args[]) throws Exception{
		System.out.println("Checking the class");
		processDocuments(12345,"eML05707f2b1c5e881378a3e7799165150dc.pdf","/home/ITGroup/testdocfiles/New/pdf/","/home/ITGroup/testdocfiles/New/");

	}


}