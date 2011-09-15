import org.apache.lucene.analysis.standard.StandardAnalyzer;
import org.apache.lucene.index.*;
import org.apache.lucene.store.*;


public class Lucene{
		
	
	public static void main(String args[]){
		String indexDirectory = "c:/__hirek";
		try{
			Directory dir = FSDirectory.getDirectory(indexDirectory, false);	
			IndexWriter indexWriter = new IndexWriter(indexDirectory, new StandardAnalyzer(), false); 
			org.apache.lucene.document.Document ldoc = new org.apache.lucene.document.Document();
			
			
			
			ldoc.add(new org.apache.lucene.document.Field(this.fields[i].getFieldName(), value, org.apache.lucene.document.Field.Store.YES, org.apache.lucene.document.Field.Index.TOKENIZED));
			indexWriter.addDocument(ldoc);  
			
			
			indexWriter.optimize();
			indexWriter.close();


		}catch(Exception e){
			System.out.println(e);
		}

	}
	
}