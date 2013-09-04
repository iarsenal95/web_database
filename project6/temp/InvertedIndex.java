package edu.umich.cse.eecs485;

import java.io.IOException;

import org.apache.hadoop.conf.Configuration;
import org.apache.hadoop.fs.Path;
import org.apache.hadoop.io.LongWritable;
import org.apache.hadoop.io.Text;
import org.apache.hadoop.mapreduce.Job;
import org.apache.hadoop.mapreduce.Mapper;
import org.apache.hadoop.mapreduce.Reducer;
import org.apache.hadoop.mapreduce.lib.input.FileInputFormat;
import org.apache.hadoop.mapreduce.lib.input.TextInputFormat;
import org.apache.hadoop.mapreduce.lib.output.FileOutputFormat;
import org.apache.hadoop.mapreduce.lib.output.TextOutputFormat;
import org.apache.mahout.classifier.bayes.XmlInputFormat;
import java.util.*;
import nu.xom.*;
//import java.util.Iterator;
import java.util.regex.Matcher;
import java.util.regex.Pattern;

//import java.util.Hashtable;
import java.io.FileReader;
import java.io.BufferedReader;
//import java.util.Set;
//import java.util.Map;
import java.lang.*;
import java.io.*;
import java.text.*;
import java.math.*;


public class InvertedIndex
{
	private static double N = 0;
	private static HashSet<String> stopwords = new HashSet<String>();
	private static Hashtable<String, Double> docNorm = new Hashtable<String, Double>();

	private static void iniStopwords(String stopWordsPath) throws Exception{
		BufferedReader inputStream = null;
		
    	inputStream = new BufferedReader(new FileReader(stopWordsPath));
		String l;
        while ((l = inputStream.readLine()) != null)
		{
			stopwords.add(l);
        }
 		if (inputStream != null)
		{
			inputStream.close();
		}
		
	}

	public static class tfMap extends Mapper<LongWritable, Text, Text, Text> {
		public void map(LongWritable key, Text value, Context context)
				throws IOException, InterruptedException {

			String strId = "";
			String strBody = "";

			// Parse the xml and read data (page id and article body)
			// Using XOM library
			Builder builder = new Builder();

			try {
//				System.out.println(value.toString());
				Document doc = builder.build(value.toString(), null);

				Nodes nodeId = doc.query("//eecs485_article_id");
				strId = nodeId.get(0).getChild(0).getValue();
				
				Nodes nodeBody = doc.query("//eecs485_article_body");
				strBody = nodeBody.get(0).getChild(0).getValue();
			}
			// indicates a well-formedness error
			catch (ParsingException ex) { 
				System.out.println("Not well-formed.");
				System.out.println(ex.getMessage());
			}  
			catch (IOException ex) {
				System.out.println("io exception");
			}
			
			// update N
			N++;

			// Tokenize document body
			Pattern pattern = Pattern.compile("\\w+");
			Matcher matcher = pattern.matcher(strBody);
			
			Hashtable<String, Integer> termfreq = new Hashtable<String, Integer>();
			while (matcher.find()){
				// Write the parsed token
				// check if this term is in stopwords, if not, update the termfreq
				String term = matcher.group();
				term = term.toLowerCase();
				if (!stopwords.contains(term)){
					if (termfreq.containsKey(term)){
						termfreq.put(term, termfreq.get(term)+1);
					}
					else
						termfreq.put(term, 1);
				}
			}
			Set set = termfreq.entrySet();
			Iterator it = set.iterator();
			while (it.hasNext()){
      			Map.Entry<String, Integer> entry = (Map.Entry<String, Integer>) it.next();
				int freq = entry.getValue();
				String term = entry.getKey();
				String s = strId + " " + freq;
				context.write(new Text(term), new Text(s));
			}
		}
	}

	public static class tfReduce extends Reducer<Text, Text, Text, Text> {
		
		public void reduce(Text key, Iterable<Text> values, Context context)
				throws IOException, InterruptedException {
			
			Hashtable<String, Double> docfreq = new Hashtable<String, Double>();
			
			String doclist = "";
			
			for (Text value : values){
				//System.out.println("here");
				String line = String.valueOf(value);
				StringTokenizer tokenizer = new StringTokenizer(line);
				String docid = tokenizer.nextToken();
				String termfreq = tokenizer.nextToken();
				//System.out.println("docid: " + docid);
				//System.out.println("termfreq: " + termfreq);
				double tf = Double.parseDouble(termfreq);
				docfreq.put(docid, tf);
			}
			int n = docfreq.size();
			double itf = Math.log(N/n);
			Set set = docfreq.entrySet();
			Iterator it = set.iterator();
			while (it.hasNext()){
				Map.Entry<String, Double> entry = (Map.Entry<String, Double>) it.next();
				double tf = itf*entry.getValue();
				String docid = entry.getKey();
				doclist += docid + ":" + String.valueOf(tf) + " ";
			}
			doclist = n + " " + doclist;
			context.write(key, new Text(doclist));
		}
	}

	public static class normMap extends Mapper<LongWritable, Text, Text, Text>{

		private Text doc = new Text();
		public void map(LongWritable key, Text value, Context context)
			throws IOException, InterruptedException
		{
				//System.out.println(value.toString()); 

				String line;
				BufferedReader reader = new BufferedReader(new StringReader(value.toString()));
				while ((line = reader.readLine()) != null)
				{
					StringTokenizer tokenizer = new StringTokenizer(line);
					String term = tokenizer.nextToken();
					String docfreq = tokenizer.nextToken();
					while(tokenizer.hasMoreTokens())
					{
						String temp = tokenizer.nextToken();
						int index = temp.indexOf(":");
						String docid = temp.substring(0, index);
						String tf = temp.substring(index+1);
						doc.set(docid);
						String termtf = term + " " + tf;
						context.write(doc, new Text(termtf));
					}		
				}
		}	
	}

	// key is docid, value is term tf
	public static class normReduce extends Reducer<Text, Text, Text, Text> {
		
		public void reduce(Text key, Iterable<Text> values, Context context)
				throws IOException, InterruptedException
		{
			double norm = 0;			
			for (Text value: values)
			{
				StringTokenizer tokenizer = new StringTokenizer(String.valueOf(value));
				String term = tokenizer.nextToken();
				double tf = Double.parseDouble(tokenizer.nextToken());
				norm += Math.pow(tf, 2);
			}
			norm = Math.sqrt(norm);
			docNorm.put(String.valueOf(key), norm);
		}
	}



	public static class invIndexMap extends Mapper<LongWritable, Text, Text, Text>
	{
		public void map(LongWritable key, Text value, Context context)
				throws IOException, InterruptedException
		{		
			String line;
			BufferedReader reader = new BufferedReader(new StringReader(value.toString()));
			while ((line = reader.readLine()) != null)
			{
				StringTokenizer tokenizer = new StringTokenizer(line);
				String term = tokenizer.nextToken();
				String docfreq = tokenizer.nextToken();
				String doclist = "";
				while(tokenizer.hasMoreTokens())
				{
					String temp = tokenizer.nextToken();
					int index = temp.indexOf(":");
					String docid = temp.substring(0, index);
					double tf = Double.parseDouble(temp.substring(index+1));
					double docnorm = docNorm.get(docid);
					double tfitf = 0;
					if (Math.abs(docnorm - 0) > 0.0000001){
						tfitf = tf/docnorm;
					}
					NumberFormat formatter = new DecimalFormat();
					formatter = new DecimalFormat("0.####E0");
					doclist += docid + ":" + formatter.format(tfitf) + " ";
				}
				context.write(new Text(term), new Text(docfreq + " " + doclist));		
			}
			
		}
	}

	public static class invIndexReduce extends Reducer<Text, Text, Text, Text> {
		
		public void reduce(Text key, Iterable<Text> values, Context context)
				throws IOException, InterruptedException{
			for (Text value: values)
			{
				context.write(key, value);
			}
		}
	}


	


	public static void tfMapReduce(String inputPath, String outputPath, String stopWordsPath) throws Exception
	{
		iniStopwords(stopWordsPath);		
		Configuration conf = new Configuration();

		conf.set("xmlinput.start", "<eecs485_article>");
		conf.set("xmlinput.end", "</eecs485_article>");
	
		Job job = new Job(conf, "getTf");

		job.setOutputKeyClass(Text.class);
		job.setOutputValueClass(Text.class);

		job.setMapperClass(tfMap.class);
		job.setReducerClass(tfReduce.class);

		job.setInputFormatClass(XmlInputFormat.class);
		job.setOutputFormatClass(TextOutputFormat.class);

		FileInputFormat.addInputPath(job, new Path(inputPath));
		FileOutputFormat.setOutputPath(job, new Path(outputPath));

		job.waitForCompletion(true);
	}

	
	public static void normMapReduce(String inputPath, String outputPath) throws Exception
	{
		Configuration conf = new Configuration();

		Job job = new Job(conf, "getNorm");

		job.setOutputKeyClass(Text.class);
		job.setOutputValueClass(Text.class);

		job.setMapperClass(normMap.class);
		job.setReducerClass(normReduce.class);

		job.setInputFormatClass(TextInputFormat.class);
		job.setOutputFormatClass(TextOutputFormat.class);

		FileInputFormat.addInputPath(job, new Path(inputPath));
		FileOutputFormat.setOutputPath(job, new Path(outputPath));

		job.waitForCompletion(true);
	}

	
	
	public static void invIndexMapReduce(String inputPath, String outputPath) throws Exception
	{
		Configuration conf = new Configuration();

		Job job = new Job(conf, "getInvIndex");

		job.setOutputKeyClass(Text.class);
		job.setOutputValueClass(Text.class);

		job.setMapperClass(invIndexMap.class);
		job.setReducerClass(invIndexReduce.class);

		job.setInputFormatClass(TextInputFormat.class);
		job.setOutputFormatClass(TextOutputFormat.class);

		FileInputFormat.addInputPath(job, new Path(inputPath));
		FileOutputFormat.setOutputPath(job, new Path(outputPath));

		job.waitForCompletion(true);
	}
	
	// args[0] input xml file
	// args[1] output file
	// args[2] stopwords
	public static void main(String[] args) throws Exception
	{
		String tfInputPath = args[0];
		//String tfOutputPath = args[1];
		String tfOutputPath = "tfoutput";
		String normInputPath = "tfoutput/part-r-00000";
		String normOutputPath = "normoutput";
		String invIndexInputPath = normInputPath;
		String invIndexOutputPath = args[1];
		String stopWordsPath = args[2];
		
		tfMapReduce(tfInputPath, tfOutputPath, stopWordsPath);
		normMapReduce(normInputPath, normOutputPath);
		invIndexMapReduce(invIndexInputPath, invIndexOutputPath);
	}
}
























