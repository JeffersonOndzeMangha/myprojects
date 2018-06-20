using System;
using System.Collections;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Data;
using System.Data.SqlClient;
using System.Threading.Tasks;
using System.Web;
using System.IO;
using System.Web.UI.WebControls;
using System.Net;
using HtmlAgilityPack;

namespace cycletraderscraper
{
    class Program
    {
        string connString = "Data Source=DESKTOP-JKV1GIF\\SQLEXPRESS;Initial Catalog=ScraperBikes;Integrated Security=True";
        DataTable dataTable = new DataTable();
        HtmlDocument htmlDoc = new HtmlDocument();
        HtmlNode bodyNode = default(HtmlNode);
        string tempDoc = string.Empty;
        HtmlWeb getHtmlWeb = new HtmlWeb();
        string cycletraderUrl = "https://www.cycletrader.com";
        List<string> listingUrlsList = new List<string>();
        string[] yearPair = default(string[]);
        string year1 = string.Empty;
        string year2 = string.Empty;

        static void Main(string[] args)
        {
            Program This = new Program();
            Console.Title = "CycleTrader Scraper V 2.0.0";
            Console.WriteLine();
            Console.WriteLine("**** THE PROGRAM HAS STARTED ****");
            Console.WriteLine();

            Console.WriteLine("Click enter to continue");

            Console.ReadKey();

            Console.Clear();

            This.getData();

            int numberOfpages = This.GetPageCount(searchToken);
            Console.WriteLine(numberOfpages);

            This.GetListings(numberOfpages, searchToken);
            This.ProcessData();
            This.SaveToDb();

            Console.WriteLine("Process Complete");

            Console.ReadLine();
        }


        private void getData()
        {
            try
            {
                using (SqlConnection cs = new SqlConnection(connString))
                {//Once connected, open connection and read data
                    string queryCapp = "SELECT * From leads ORDER BY listingUrl;";
                    SqlCommand cmdSC = new SqlCommand(queryCapp, cs);

                    cs.Open();
                    SqlDataAdapter adapter = new SqlDataAdapter();
                    adapter.SelectCommand = cmdSC;
                    adapter.Fill(dataTable);
                }
            }
            catch (Exception ex)
            {
                Console.WriteLine(ex);
            }
        }

        private void SaveToDb()
        {
            try
            {
                using (SqlConnection cs = new SqlConnection(connString)){
                    cs.Open();
                    SqlCommand command = new SqlCommand();
                    //Creating temp table on database
                    command.CommandText = "IF EXISTS (SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = N'temp_tbl')BEGIN DROP TABLE temp_tbl END";
                    command.Connection = cs;
                    command.ExecuteNonQuery();
                    command.CommandText = "SELECT * INTO temp_tbl FROM leads WHERE 1=2";
                    command.Connection = cs;
                    command.ExecuteNonQuery();

                    //Bulk insert into temp table
                    using (SqlBulkCopy bulkcopy = new SqlBulkCopy(cs))
                    {
                        bulkcopy.BulkCopyTimeout = 660;
                        bulkcopy.DestinationTableName = "temp_tbl";
                        bulkcopy.WriteToServer(dataTable);
                        bulkcopy.Close();
                    }

                    // Updating destination table, and dropping temp table
                    command.CommandTimeout = 300;
                    command.CommandText = "MERGE INTO leads T USING (SELECT * FROM temp_tbl) S ON T.listingUrl = S.listingUrl and T.year = S.year WHEN NOT MATCHED THEN INSERT (listingUrl, Condition, year, Make, Model, Type, Location, Category, Mileage, VINNumber, PrimaryColor, SecondaryColor, EngineType, Options, EngineSize, Source) VALUES (S.listingUrl, S.Condition, S.year, S.Make, S.Model, S.Type, S.Location, S.Category, S.Mileage, S.VINNumber, S.PrimaryColor, S.SecondaryColor, S.EngineType, S.Options, S.EngineSize, S.Source) WHEN MATCHED THEN UPDATE SET T.Condition = S.Condition, T.year = S.year, T.Make = S.Make, T.Model = S.Model, T.Type = S.Type, T.Location = S.Location, T.Category = S.Category, T.Mileage = S.Mileage, T.VINNumber = S.VINNumber, T.PrimaryColor = S.PrimaryColor, T.SecondaryColor = S.SecondaryColor, T.EngineType = S.EngineType, T.Options = S.Options, T.EngineSize = S.EngineSize, T.Source = S.Source; DROP table dbo.temp_tbl";
                    command.Connection = cs;
                    command.ExecuteNonQuery();
                }
            }
            catch (Exception ex)
            {
                Console.WriteLine(ex);
            }
        }

        private int GetPageCount(string token)
        {
            // There are various options, set as needed
            this.htmlDoc.OptionFixNestedTags = true;
            htmlDoc.OptionAddDebuggingAttributes = true;

            getHtmlWeb = new HtmlWeb();
            tempDoc = getHtmlWeb.Load("https://www.cycletrader.com/search-results?type=Motorcycle%7C356953&sort=featured%3Aasc&radius=any&year="+year1+"%3A"+year2+"&page=1&keyword="+token+"&condition=U&make=Harley-Davidson%7C2316294&seller_type=NT%2CCSNT%2CPM&").DocumentNode.InnerHtml;
            //Console.WriteLine(htmlDoc1);
            htmlDoc.LoadHtml(tempDoc);
            // ParseErrors is an ArrayList containing any errors from the Load statement
            if (htmlDoc.ParseErrors != null && htmlDoc.ParseErrors.Count() > 0)
            {
                foreach (var error in htmlDoc.ParseErrors)
                {
                    Console.WriteLine(error.Code);
                }
            }
            else
            {
                if (htmlDoc.DocumentNode != null)
                {
                    bodyNode = htmlDoc.DocumentNode.SelectSingleNode("//body//div[@id='wrapper']//section[@id='page-content-wrapper']//section[@class='desktop-overflow-wrapper']//div[@id='listings']//section[@id='searchPage']//div[@id='listingsContainer']//footer[@class='showMore']//div[@class='pagination margin10T']//ul[@class='list-unstyled']//span[@class='hidden-xs']//li//a[@ data-track='Search - Pagination - Bottom - Last Link']");
                    if (bodyNode != null)
                    {
                    }
                    else
                    {
                        return 0;
                    }
                }
            }
            return Int32.Parse(bodyNode.GetAttributeValue("data-page", ""));
        }

        private void GetListings(int pageCount, string token)
        {
            if(pageCount != 0)
            {
                for(int pageNumber =1; pageNumber<pageCount; pageNumber++)
                {
                    tempDoc = getHtmlWeb.Load("https://www.cycletrader.com/search-results?type=Motorcycle%7C356953&sort=featured%3Aasc&radius=any&year="+year1+"%3A"+year2+"&page="+pageNumber+"&keyword="+token+"&condition=U&make=Harley-Davidson%7C2316294&seller_type=NT%2CCSNT%2CPM&").DocumentNode.InnerHtml;
                    htmlDoc.LoadHtml(tempDoc);
                    if (htmlDoc.ParseErrors != null && htmlDoc.ParseErrors.Count() > 0)
                    {
                        foreach (var error in htmlDoc.ParseErrors)
                        {
                            Console.WriteLine(error.Code);
                        }
                    }
                    else
                    {
                        if (htmlDoc.DocumentNode != null)
                        {
                            bodyNode = htmlDoc.DocumentNode.SelectSingleNode("//body//div[@id='wrapper']//section[@id='page-content-wrapper']//section[@class='desktop-overflow-wrapper']//div[@id='listings']//section[@id='searchPage']//div[@id='listingsContainer']//section//div[@id='gridView']");
                            HtmlNodeCollection listingsDivs = htmlDoc.DocumentNode.SelectNodes("//body//div[@id='wrapper']//section[@id='page-content-wrapper']//section[@class='desktop-overflow-wrapper']//div[@id='listings']//section[@id='searchPage']//div[@id='listingsContainer']//section//div[@id='gridView']//div");
                            Console.WriteLine("Page: "+pageNumber+" completed");
                            if (bodyNode != null && listingsDivs != null)
                            {
                                // Do something with bodyNode
                                foreach(HtmlNode node in listingsDivs)
                                {
                                    try
                                    {
                                        string listingUrl = cycletraderUrl + node.SelectSingleNode("//div[@class='listing-info listing-info-bottom']//a[@class='listing-info-title']").GetAttributeValue("href", " ");
                                        if (!listingUrlsList.Contains(listingUrl))
                                        {
                                            listingUrlsList.Add(listingUrl);
                                        }
                                    }
                                    catch(Exception ex)
                                    {
                                        Console.WriteLine(ex);
                                    }
                                }
                            }
                        }

                    }

                }

            }
            else
            {
                Console.WriteLine("No pages were found, this bike is not listed on cycletrader");
            }
        }

        private void ProcessData()
        {
            foreach(string listingUrl in listingUrlsList)
            {
                tempDoc = getHtmlWeb.Load(listingUrl).DocumentNode.InnerHtml;
                htmlDoc.LoadHtml(tempDoc);
                HtmlNodeCollection liRemove = htmlDoc.DocumentNode.SelectNodes("//body//div[@class='desktop-overflow-wrapper']//section[@id='details']//section[@class='info-section padding10']//div[@id='info-list-seller']//ul[@class='info-list-seller list-unstyled']//li[@class='hidden-lg']");
                bodyNode = htmlDoc.DocumentNode.SelectSingleNode("//body//div[@class='desktop-overflow-wrapper']//section[@id='details']//section[@class='info-section padding10']//div[@id='info-list-seller']//ul[@class='info-list-seller list-unstyled']");
                try
                {
                    foreach (HtmlNode li in liRemove)
                    {
                        bodyNode.RemoveChild(li, false);
                    }
                }catch(Exception ex)
                {
                    Console.WriteLine(ex);
                }
                HtmlNodeCollection listingDataValues = bodyNode.ChildNodes;
                DataRow[] rowsFound = dataTable.Select("listingUrl = '" + listingUrl + "'");
                DataRow newRow = dataTable.NewRow();


                foreach (HtmlNode listingDataValue in listingDataValues)
                {
                    string[] valuePair = GetValuePairListingData(listingDataValue.InnerText);

                    string key = valuePair.First<string>().Trim();
                    string value = valuePair.Last<string>().Trim();
                    if(key != "" && value != "")
                    {
                        string keyF = RemoveWhiteSpaces(key);

                        Console.WriteLine(keyF+":"+value);

                        if(keyF == "Condition" || key == "Condition")
                        {
                            newRow["Condition"] = value;
                        }

                        if (keyF == "Year" || key == "Year")
                        {
                            newRow["year"] = value;
                        }
                       
                        if (keyF == "Make" || key == "Make")
                        {
                            newRow["Make"] = value;
                        }
                  
                        if (keyF == "Model")
                        {
                            newRow["Model"] = value;
                        }
                        

                        if (keyF == "Type")
                        {
                            newRow["Type"] = value;
                        }
                        
                        if (keyF == "Location")
                        {
                            newRow["Location"] = value;
                        }
                       
                        if (keyF == "Category")
                        {
                            newRow["Category"] = value;
                        }
                        
                        if (keyF == "Mileage")
                        {
                            newRow["Mileage"] = value;
                        }
                        
                        if (keyF == "VINNumber" || key == "VIN Number")
                        {
                            newRow["VINNumber"] = value;
                        }

                        if (keyF == "PrimaryColor")
                        {
                            newRow["PrimaryColor"] = value;
                        }
                        
                        if (keyF == "SecondaryColor")
                        {
                            newRow["SecondaryColor"] = value;
                        }

                        if (keyF == "EngineType")
                        {
                            newRow["EngineType"] = value;
                        }
                        
                        if (keyF == "Options")
                        {
                            newRow["Options"] = value;
                        }
                        
                        if (keyF == "EngineSize")
                        {
                            newRow["EngineSize"] = value;
                        }
                        
                        Boolean foundCols = dataTable.Columns.Contains(keyF);
                        if (foundCols)
                        {

                        }else if (!foundCols)
                        {
                            dataTable.Columns.Add(keyF);
                        }
                        if(rowsFound.Length > 0)
                        {
                            foreach (DataRow frow in rowsFound)
                            {
                                frow[keyF] = value;
                            }
                        }
                        else
                        {
                            newRow[keyF] = value;
                        }
                    }
                }
                Console.WriteLine();
                newRow["contacted"] = false;
                newRow["listingUrl"] = listingUrl;
                newRow["Source"] = "CycleTrader";
                dataTable.Rows.Add(newRow);
            }
        }

        static string ChangeSpacesToPercent(string token)
        {
            return token.Replace(" ", "%20");
        }
        static string[] GetValuePairListingData(string lidata)
        {
            return lidata.Split(':');
        }
        static string RemoveWhiteSpaces(string str)
        {
            return str.Replace(" ", "");
        }
    }
}
