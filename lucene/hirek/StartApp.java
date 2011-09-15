import java.io.IOException;

public class StartApp{
	public static void main(String[] args){
		if (args.length > 0){
			StringBuffer cmd = new StringBuffer();
			for (int index = 0; index < args.length; index++){
				cmd.append(args[index] + " ");
			}
			try{
				Runtime.getRuntime().exec(cmd.toString());
			}catch (IOException ioe){
				System.out.println("Error: command not found: " + cmd.toString());
			}
		}else{
			System.out.println("Error: missing arguments");
		}
	}
}