import freemarker.cache.*;
import freemarker.core.*;
import freemarker.debug.*;
import freemarker.log.*;
import freemarker.template.*;
import java.lang.*;
import java.util.*;
import java.io.*;



class Templates{
	public static void main(String arg[]){
		try{
			Configuration cfg = new Configuration();
			cfg.setDirectoryForTemplateLoading(new File("c:/work/Hirek.hu/motor/templates/"));
			cfg.setObjectWrapper(new DefaultObjectWrapper());
			
			// Create the root hash
			Map root = new HashMap();
			// Put string ``user'' into the root
			root.put("variable", "Big Joe");
			
			String[] test = {"1", "fdsfsd", "333"};									
			root.put("x", test);
			
			Template temp = cfg.getTemplate("index.html");  
			BufferedWriter out = new BufferedWriter(new FileWriter("c:/test.test"));
	        temp.process(root, out);
	        out.close();
			
			
			/*Writer out = new OutputStreamWriter(System.out);
			
			out.flush();  */
		}catch(Exception e){
			System.out.println(e);
		}
	}
}