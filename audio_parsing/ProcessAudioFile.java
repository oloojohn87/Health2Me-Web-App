import java.io.File;
import java.io.IOException;

import org.apache.commons.io.IOUtils;
import org.apache.http.HttpResponse;
import org.apache.http.client.ClientProtocolException;
import org.apache.http.client.HttpClient;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.entity.FileEntity;
import org.apache.http.impl.client.DefaultHttpClient;


import javaFlacEncoder.FLAC_FileEncoder;


public class ProcessAudioFile {

public String processfile(String inputfilename,String outputfilename){
	// get an encoder
	FLAC_FileEncoder flacEncoder = new FLAC_FileEncoder();
	String parse_text="";
	/*System.out.println("first:"+inputfilename);
	System.out.println("second:"+outputfilename);*/
	// point to the input file
	File inputFile = new File(inputfilename);
	File outputFile = new File(outputfilename);
	 
	// encode the file
	//LOG.info("Start encoding wav file to flac.");
	flacEncoder.encode(inputFile, outputFile);
	//LOG.info("Finished encoding wav file to flac.");
	
	//LOG.info("Sending file to google for speech2text");

	HttpClient client = new DefaultHttpClient();

	String URL="https://www.google.com/speech-api/v1/recognize?xjerr=1&client=chromium&lang=en-US";
	
	HttpPost p = new HttpPost(URL);
	
	p.addHeader("Content-Type", "audio/x-flac; rate=44100");
	
	p.setEntity(new FileEntity(outputFile, "audio/x-flac; rate=44100"));
	
	HttpResponse response;
	try {
		response = client.execute(p);
	
	if (response.getStatusLine().getStatusCode() == 200) {
		//LOG.info("Received valid response, sending back to browser.");
		String result = new String(IOUtils.toByteArray(response.getEntity().getContent()));
		//this.connection.sendMessage(result);
		//{"status":0,"id":"d9a6f941d42797c2102dba815b32a405-1","hypotheses":[{"utterance":"hello hello hello","confidence":0.94610506}]}
		parse_text=result.substring(result.indexOf("utterance\":")+12,result.indexOf("\",\"confidence"));
		System.out.println(result.substring(result.indexOf("utterance\":")+12,result.indexOf("\",\"confidence")));
	}
	
	} catch (ClientProtocolException e) {
		// TODO Auto-generated catch block
		e.printStackTrace();
	} catch (IOException e) {
		// TODO Auto-generated catch block
		e.printStackTrace();
	} catch (IndexOutOfBoundsException e){
			
		return "Speech could not be converted into text";
	}

	return parse_text;
}

}


