import java.util.logging.*;
import java.util.*;
import java.io.*;

public class StatLogger{
	private String className = "";
	private java.util.logging.Logger log = null;
		
	public StatLogger(String className, String message){
		try{
			this.className = className;
			this.log = java.util.logging.Logger.getLogger(this.className);					
			Calendar cal = new GregorianCalendar();				    					
			FileHandler handler = new FileHandler(cal.get(Calendar.YEAR) + "_"+ cal.get(Calendar.MONTH) + "_" + cal.get(Calendar.DAY_OF_MONTH) +  ".statistics.html", true);			
		
	        int numRec = 100;
	        MemoryHandler mhandler = new MemoryHandler(handler, numRec, Level.OFF) {	        	
	       	public synchronized void publish(LogRecord record) {
	                super.publish(record);
	                boolean condition = false;
	                if (condition) {                
	                    push();
	                }
	            }
	        };            	        
			
			handler.setFormatter(new MyHtmlFormatter());
			this.log.addHandler(handler);
			this.log.setUseParentHandlers(false);
			this.log.log(Level.INFO, message);			
		}catch(IOException e){			
		}		
	}
}
	        