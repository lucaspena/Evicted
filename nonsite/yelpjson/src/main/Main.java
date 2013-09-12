/*
 * This loads the Yelp data into the database, along with a category we assigned
 * to each business. Tables are created previously in the mySQL command line.
 */
package main;

import java.io.BufferedInputStream;
import java.io.BufferedReader;
import java.io.File;
import java.io.FileInputStream;
import java.io.InputStreamReader;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.Statement;
import java.util.ArrayList;

import com.google.gson.Gson;
import com.google.gson.JsonObject;
import com.google.gson.JsonParser;

public class Main {
	public static void main(String[] args) throws Exception {
		// Initialize a gson variable
		Gson gson = new Gson();

		ArrayList<Business> businesses = new ArrayList<Business>();
		ArrayList<Review> reviews = new ArrayList<Review>();

		// Decodes dataset - we only use the Business and Review tables
		File file = new File("yelp_academic_dataset.json");
		FileInputStream fis = new FileInputStream(file);
		BufferedInputStream bis = new BufferedInputStream(fis);
		BufferedReader d = new BufferedReader(new InputStreamReader(bis));
		String line = "";
		while ((line = d.readLine()) != null) {
			try {
				JsonObject o = new JsonParser().parse(line).getAsJsonObject();
				String s = o.get("type").getAsString();

				if (s.equals("review")) {
					reviews.add(gson.fromJson(o, Review.class));
				} 
				else if (s.equals("business")) {
					businesses.add(gson.fromJson(o, Business.class));
				} 
			} catch (Exception e) {
				e.printStackTrace();
				System.out.println("Invalid data format.");
				System.exit(0);
			}
		}
		fis.close();
		bis.close();
		d.close();

		// A simple test case to check if data has been read properly and
		// decoded
		if (reviews.get(0).votes.cool == 1) {
			System.out.println("Reviews have been succesfully read");
			System.out.println();
		}
		if (businesses.get(1).city.equals("Cambridge")) {
			System.out.println("Businesses have been successfully read");
			System.out.println();
		}

		/* Insert all of the data into database
		 Initializes the database. We manually CREATE TABLE (or DROP TABLE
		 in mySQL because we kept having to make changes to the schema, so it
		 isn't used here. */
		Statement st = null;
		st = makeConnectionWithDatabase(args);
		st.setEscapeProcessing(true);
		DMLs(businesses, reviews, st);
		st.close();
	}
	// Makes connection with fling database
	public static Statement makeConnectionWithDatabase(String[] args)
			throws Exception {
		try {
			Class.forName("com.mysql.jdbc.Driver").newInstance();
			Connection conn = null;
			conn = DriverManager.getConnection("jdbc:mysql://fling.seas.upenn.edu:3306/tmandel","tmandel","abc123");
			Statement st = conn.createStatement();
			return st;
		} catch (Exception e) {
			e.printStackTrace();
			System.out.println(e);
		}
		return null;
	}

	/* We sort all our businesses into categories so each button in the game
	 * corresponds to a category of business to visit (e.g. restaurant). These
	 * are ordered so that something that is a Hotel with food won't accidentally
	 * get lumped with a restaurant, or a Park that is in both "Parks" and
	 * "Museum" would end up in "Work Out" instead of in "See The Sights".*/

	private static String getCat(ArrayList<String> category){
		for (String s : category) { //Go Rest
			if ((s.contains("Apartment") || s.equals("Hotels") ||
					s.contains("Hostel") || s.contains("Bed & Breakfast") ||
					s.contains("Retirement Home")|| s.contains("University Housing")) 
					&& !s.contains("Realty") && !s.contains("Realtor")
					&& !s.contains("Taxi") && !s.contains("Real Estate")) return "slp";
		}
		for (String s : category) { //Get Food
			if (s.contains("Food") || s.contains("Restaurant")) return "hun";
		}
		for (String s : category) { //Be Healthy
			if (s.contains("Health") || s.contains("Doctor") ||
					s.contains("Medical") || s.contains("Hospital")) return "med";
		}
		/*		for (String s : category) {
			if (s.contains("Auto") ||
					s.contains("Bike") || s.contains("Car") ||
					s.contains("Home") || s.contains("Clothing")) return "com";
		}*/
		for (String s : category) { //Work Out
			if (s.contains("Parks") || s.contains("Gyms") ||
					s.contains("Playgrounds") || s.contains("Recreation Centers")) return "str";
		}
		for (String s : category) { //Go Out
			if (s.contains("Nightlife") || s.contains("Social Clubs")) return "soc";
		}
		for (String s : category) { //See The Sights
			if (s.contains("Adult") || s.contains("Art") ||
					s.contains("Museum") || s.contains("Cinema") ||
					s.contains("Bowling") || s.contains("Arcade") ||
					s.contains("Performing Arts")) return "art";
		}
		return "oth"; //We aren't using other businesses in our game
	}

	/* 
	 * Puts reviews in Review table and puts businesses (and category that 
	 * we had determined) in Business table. Removes ' and \ in strings.
	 */
	public static void DMLs(ArrayList<Business> businesses, ArrayList<Review> reviews, Statement st) {
		for (Business b : businesses) {
			try {
				b.name = b.name.replace("'",  "\\'");
				b.name = b.name.replace("\"", "\\\"");
				if (b.name.endsWith("\\")) b.name = b.name.substring(0, b.name.length() - 1);
				b.full_address = b.full_address.replace("'",  "\\'");
				b.full_address = b.full_address.replace("\"", "\\\"");
				if (b.full_address.endsWith("\\")) b.full_address = b.full_address.substring(0, b.full_address.length() - 1);
				String temp1a = "INSERT INTO Business VALUES ('" + b.business_id + "', '" + b.name + "', '" + b.full_address + "', "
						+ b.latitude + ", " + b.longitude + ", '" + b.photo_url + "', '" + b.city + "', '" + b.state + "', "
						+ b.review_count + ", '" + b.url + "', " + (b.stars * 20) + ", '" + getCat(b.categories) + "')";
				st.execute(temp1a);
			} catch (Exception e) {
				e.printStackTrace();
			}
		}
		for (Review r : reviews) {
			try {
				r.text = r.text.replace("'",  "\\'");
				r.text = r.text.replace("\"", "\\\"");
				if (r.text.endsWith("\\")) r.text = r.text.substring(0, r.text.length() - 1);
				//System.out.println(r.text);
				String temp2a = "INSERT INTO Review VALUES ('" + r.business_id + "', '" + r.review_id + "', " + r.votes.useful + ", " 
						+ r.votes.funny + ", " + r.votes.cool + ", " + (r.stars * 20) + ", '" + r.text + "')"; 
				st.execute(temp2a);
			} catch (Exception e) {
				e.printStackTrace();
				System.out.println(r.text);
			}
		}		
	}
}