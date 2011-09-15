import java.util.logging.*;
import java.util.Date;

public class MyHtmlFormatter extends Formatter {
	public String format(LogRecord rec){
		StringBuffer buf = new StringBuffer(1000);
		if (rec.getLevel().intValue() >= Level.WARNING.intValue()) {
			buf.append("<b>");
			buf.append(rec.getLevel());
			buf.append("</b>");
    	}else{
    		buf.append(rec.getLevel());
    	}
    	buf.append(' ');
    	buf.append(formatMessage(rec));
    	buf.append("<br />\r\n");
    	return buf.toString();
    }
    
    public String getHead(Handler h) {
    	return ""+(new Date())+"<br />\r\n";
    }
    
    public String getTail(Handler h) {
    	return "<hr />\r\n";
    }
}