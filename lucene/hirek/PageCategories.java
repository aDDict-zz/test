import java.util.ArrayList;

public class PageCategories{
	private String categoryName = null;
	private int categoryColumn = 0;
	private int categoryPosition = 0;
	public ArrayList rss = null;
	
	public PageCategories(){
	}
		
	public PageCategories(String categoryName, int categoryColumn, int categoryPosition, ArrayList rss){
		this.categoryName = categoryName;
		this.categoryColumn = categoryColumn;
		this.categoryPosition =  categoryPosition;
		this.rss = rss;
		
	}
	public String getCategoryName(){
		return this.categoryName;
	}
	
	public int getCategoryColumn(){
		return this.categoryColumn;
	}
	
	public int getCategoryPosition(){
		return this.categoryPosition;
	}
	
	public ArrayList getRss(){
		return this.rss;
	}
}