import java.util.Vector;

import com.jcraft.jsch.*;

public class connectZennith {
	

	private static String strServer = "remote.zenithtranscriptions.com";
	private static String strServerPort = "22";
	private static String strServerUsername = "5000ubi-emr";
	private static String strServerPassword = "0295";
    public static void main(String args[]) {
        
    }
    
    public static void putAudio(String srcfilename,String dstfilename){
    	JSch jsch = new JSch();
        Session session = null;
        try {
            session = jsch.getSession(strServerUsername, strServer, 22);
            session.setConfig("StrictHostKeyChecking", "no");
            session.setPassword(strServerPassword);
            session.connect();

            Channel channel = session.openChannel("sftp");
            channel.connect();
            ChannelSftp sftpChannel = (ChannelSftp) channel;
            String rpath="/Message/Health2me";
            sftpChannel.cd(rpath);
            //sftpChannel.get("702_0295.MP3","702_0295.MP3");
            sftpChannel.put(srcfilename, dstfilename);
            //System.out.println(path);
           // sftpChannel.get("remotefile.txt", "localfile.txt");
            sftpChannel.exit();
            session.disconnect();
        } catch (JSchException e) {
            e.printStackTrace();  
        } catch (SftpException e) {
            e.printStackTrace();
        }
    }
}