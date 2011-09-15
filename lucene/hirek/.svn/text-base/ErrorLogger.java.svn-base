import java.util.logging.*;
import java.util.*;
import java.io.*;

public class ErrorLogger{
	private String className = "";
	private Logger log = null;
		
	public ErrorLogger(String className, Level level, String message){
		try{
			this.className = className;
			this.log = Logger.getLogger(this.className);					
			Calendar cal = new GregorianCalendar();				    					
			FileHandler handler = new FileHandler(cal.get(Calendar.YEAR) + "_"+ cal.get(Calendar.MONTH) + "_" + cal.get(Calendar.DAY_OF_MONTH) +  ".error.html", true);			
			handler.setFormatter(new MyHtmlFormatter());
			this.log.addHandler(handler);
			this.log.setUseParentHandlers(false);
			//this.log.log(level, message);			
		}catch(IOException e){			
		}		
	}
}