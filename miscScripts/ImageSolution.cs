using System;
using System.Data;
using System.Net;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.IO;
using System.Data.SqlClient;

namespace ImgSolutionApp
{
    class Program
    {
        static void Main(string[] args)
        {// Connecting to SQL Server

            string conString = "Data Source=52.23.150.242;Initial Catalog=Rumbleon_QA;User ID=DBAdmin;Password=f#!@0m!n;MultipleActiveResultSets=True";
            DataSet tableset = new DataSet();
            using (SqlConnection cs = new SqlConnection(conString))
            {//Once connected, open connection and read data
                string queryCapp = "SELECT * From dbo.capp_inventoryJeff";
                string localspVIN = "C:/Users/jefferson/Desktop/img/Vin/";
                string localspModel = "C:/Users/jefferson/Desktop/img/Model/";
                var cmdSC = new SqlCommand(queryCapp, cs);
                var cmdM = new SqlCommand("SELECT * From dbo.BikeModelimagesJeff", cs);
                SqlDataAdapter adapter = new SqlDataAdapter()
                {
                    SelectCommand = cmdSC
                };
                if (cs != null && cs.State != System.Data.ConnectionState.Open)
                {
                    cs.Open();
                    Console.WriteLine("connection established");
                    adapter.Fill(tableset, "dbo.capp_inventoryJeff");
                    adapter.SelectCommand = cmdM;
                    adapter.Fill(tableset, "dbo.BikeModelimagesJeff");
                    DataTable cap = tableset.Tables[0];
                    DataTable bikem = tableset.Tables[1];

                    //-------=====start folder to db mirroring====-------////

                    string[] folders = Directory.GetDirectories(localspModel);
                    for (int f = 0; f < folders.Length; f++)
                    {
                        string folder = folders[f];
                        string foldername = ChangeSlashes(folder.Split('/').Last());
                        bool filep;
                        string file = default(string);
                        string[] years = default(string[]);
                        bool yearsp;
                        //Console.WriteLine(foldername);
                        try
                        {
                            file = ChangeSlashes(Directory.GetFiles(folder).OrderByDescending(d => new FileInfo(d).CreationTime).ToArray()[0]); //Directory.GetFiles(folder)[0];
                            years = Directory.GetDirectories(folder);
                            filep = true;
                            yearsp = true;
                        }
                        catch
                        {
                            filep = false;
                            yearsp = false;
                        }
                        DataTable mtable = tableset.Tables[1];
                        for (int r = 0; r < mtable.Rows.Count; r++)
                        {
                            DataRow row = mtable.Rows[r];
                            string model = RemoveWhitespace(row["Model"].ToString());
                            string modelv = row["Model"].ToString();
                            string localurl = ChangeSlashes(row["LocalUrl"].ToString());
                            string yeart = row["Year"].ToString();
                            string color = RemoveWhitespace(ChangeSlashes(row["color"].ToString()));
                            //Console.WriteLine(model);
                            if (model == foldername && yeart == "" && color == "")
                            {
                                //Console.WriteLine(model);
                                //Console.WriteLine(localurl);
                                if (filep == true && localurl != file)
                                {
                                    //Console.WriteLine(localurl);
                                    SqlCommand cmd = new SqlCommand("UPDATE dbo.BikeModelimagesJeff SET LocalUrl=@file WHERE Model=@model AND Year IS NULL AND color IS NULL", cs);//<------- check connection
                                    cmd.Parameters.AddWithValue("model", modelv);
                                    cmd.Parameters.AddWithValue("file", file);
                                    cmd.ExecuteNonQuery();
                                }
                            }
                        }
                        if (yearsp == true)
                        {
                            for (int y = 0; y < years.Length; y++)
                            {
                                string year = ChangeSlashes(years[y].ToString());
                                string yearname = ChangeSlashes(year).Split('/').Last();
                                bool yearfp;
                                string yearf = default(string);
                                string[] colors = default(string[]);
                                bool colorsp;
                                //Console.WriteLine(yearname);
                                //Console.WriteLine(foldername);
                                //Console.ReadLine();
                                try
                                {
                                    yearf = ChangeSlashes(Directory.GetFiles(year).OrderByDescending(d => new FileInfo(d).CreationTime).ToArray()[0]);//Directory.GetFiles(year)[0];
                                    colors = Directory.GetDirectories(year);
                                    yearfp = true;
                                    colorsp = true;
                                }
                                catch
                                {
                                    yearfp = false;
                                    colorsp = false;
                                }
                                for (int r = 0; r < mtable.Rows.Count; r++)
                                {
                                    DataRow row = mtable.Rows[r];
                                    string model = RemoveWhitespace(row["Model"].ToString());
                                    string modelv = row["Model"].ToString();
                                    string localurl = ChangeSlashes(row["LocalUrl"].ToString());
                                    string yeart = row["Year"].ToString();
                                    string color = RemoveWhitespace(ChangeSlashes(row["color"].ToString()));

                                    if (model == foldername)
                                    {
                                        if(yeart != "")
                                        {
                                            if(yeart == yearname)
                                            {
                                                if(color != "")
                                                {
                                                    return;
                                                }
                                                SqlCommand cmd1 = new SqlCommand("UPDATE dbo.BikeModelimagesJeff SET LocalUrl=@file WHERE Model=@model AND Year=@year AND color IS NULL", cs);//<------- check connection
                                                cmd1.Parameters.AddWithValue("model", modelv);
                                                cmd1.Parameters.AddWithValue("year", yearname);
                                                cmd1.Parameters.AddWithValue("file", yearf);
                                                cmd1.ExecuteNonQuery();

                                                return;
                                            }
                                            else
                                            {
                                                SqlCommand cmd = new SqlCommand("INSERT INTO dbo.BikeModelimagesJeff (Model, ImageUrl, BaseModel, Year, color, LocalUrl) VALUES(@foldername, @file, NULL, @year, NULL, @file)", cs);//<------- check connection
                                                cmd.Parameters.AddWithValue("foldername", modelv);
                                                cmd.Parameters.AddWithValue("year", yearname);
                                                cmd.Parameters.AddWithValue("file", yearf);
                                                cmd.ExecuteNonQuery();
                                            }
                                            
                                        }
                                    }
                                    else
                                    {
                                        Console.WriteLine(model);
                                    }
                                }
                                if (colorsp == true)
                                {
                                    for (int c = 0; c < colors.Length; c++)
                                    {
                                        string color = colors[c];
                                        string colorname = ChangeSlashes(color).Split('/').Last();
                                        bool colorfp;
                                        string colorf = "";
                                        try
                                        {
                                            colorf = ChangeSlashes(Directory.GetFiles(color).OrderByDescending(d => new FileInfo(d).CreationTime).ToArray()[0]); //Directory.GetFiles(color)[0];
                                            colorfp = true;
                                        }
                                        catch
                                        {
                                            colorfp = false;
                                        }
                                        for (int r = 0; r < mtable.Rows.Count; r++)
                                        {
                                            DataRow row = mtable.Rows[r];
                                            string model = RemoveWhitespace(row["Model"].ToString());
                                            string modelv = row["Model"].ToString();
                                            string localurl = ChangeSlashes(row["LocalUrl"].ToString());
                                            string yeart = row["Year"].ToString();
                                            string colorm = RemoveWhitespace(row["color"].ToString());
                                            if (model == foldername)
                                            {
                                                if(yeart != "")
                                                {
                                                    if(yeart == yearname)
                                                    {
                                                        if(colorm != "")
                                                        {
                                                            if(colorm == colorname)
                                                            {
                                                                SqlCommand cmd2 = new SqlCommand("UPDATE dbo.BikeModelimagesJeff SET LocalUrl=@file WHERE Model=@model AND Year=@year AND color=@color", cs);//<------- check connection
                                                                cmd2.Parameters.AddWithValue("model", modelv);
                                                                cmd2.Parameters.AddWithValue("year", yearname);
                                                                cmd2.Parameters.AddWithValue("color", colorname);
                                                                cmd2.Parameters.AddWithValue("file", colorf);
                                                                cmd2.ExecuteNonQuery();
                                                                return;
                                                            }
                                                            SqlCommand cmd = new SqlCommand("INSERT INTO dbo.BikeModelimagesJeff (Model, ImageUrl, BaseModel, id, Year, color, LocalUrl) VALUES(@foldername, @file, NULL, @year, @color, @file)", cs);//<------- check connection
                                                            cmd.Parameters.AddWithValue("foldername", modelv);
                                                            cmd.Parameters.AddWithValue("year", yearname);
                                                            cmd.Parameters.AddWithValue("color", colorname);
                                                            cmd.Parameters.AddWithValue("file", colorf);
                                                            cmd.ExecuteNonQuery();
                                                            return;
                                                        }
                                                    }
                                                }
                                            }
                                            else
                                            {
                                                Console.WriteLine("something else is wrong");
                                            }
                                        }
                                    }
                                }
                            }
                        }//---- if years exist in model folder
                    }
                    //------======= end folder to db mirroring=======-------//

                    //-----====== start db to folder mirroring=====------//

                    /*for (int i = 0; i < cap.Rows.Count; i++)
                    {
                        DataRow row = cap.Rows[i];
                        string imgs = row["IMAGEURL"].ToString();
                        string[] imgarray = imgs.Split(',');
                        string img = imgarray[0];
                        string vin = row["VIN"].ToString();
                        string modelv = row["MODEL"].ToString();
                        Console.WriteLine(img);
                        if (img != null && img.ToString() != "")
                        {
                            string thisurl = img;
                            string imgname = Removepercentsign(img.Split('/').Last());
                            string dlurl = "https://www.rumbleon.com//HttpImageHandler.ashx?ht=380&wd=250&makeTypeId=1&bucketId=5&isMobile=1&imageUrl=" + img;
                            if (!Directory.Exists(localspVIN + vin))
                            {
                                Directory.CreateDirectory(localspVIN + vin);
                            }
                            if (!File.Exists(localspVIN + vin + "/" + imgname))
                            {
                                using (WebClient downloader = new WebClient())
                                {
                                    string newurl1 = localspVIN + vin + "/" + imgname;
                                    downloader.DownloadFile(dlurl, @newurl1);
                                    SqlCommand cmd = new SqlCommand("UPDATE dbo.capp_inventoryJeff SET LOCALURL=@img WHERE VIN=@vin", cs);
                                    cmd.Parameters.AddWithValue("img", newurl1);
                                    cmd.Parameters.AddWithValue("vin", vin);
                                    int it = cmd.ExecuteNonQuery();
                                }
                            }
                            else
                            {
                                Console.WriteLine("It Exists");
                            }
                        }//------- end no img if from capp_inventory
                        else
                        {
                            var modelA = row["MODEL"];
                            string modelpath = RemoveWhitespace(modelA.ToString());
                            Console.Write(modelA);
                            ///var cmdSM = new SqlCommand("SELECT * From dbo.BikeModelimagesJeff WHERE Model=@model", cs);
                            ///cmdSM.Parameters.AddWithValue("model", modela);
                            for (int j = 0; j < bikem.Rows.Count; j++)
                            {
                                if (bikem.Rows[j]["Model"].Equals(modelA))
                                {
                                    DataRow rowb = bikem.Rows[j];
                                    string imgN = rowb["ImageUrl"].ToString();
                                    string year = rowb["Year"].ToString();
                                    string color = RemoveWhitespace(ChangeSlashes(rowb["color"].ToString()));
                                    string localurlt = rowb["LocalUrl"].ToString();
                                    string thisurl = imgN;
                                    string imgname = Removepercentsign(imgN.Split('/').Last());
                                    string dlurl = "https://www.rumbleon.com//HttpImageHandler.ashx?ht=380&wd=250&makeTypeId=1&bucketId=5&isMobile=1&imageUrl=" + thisurl;
                                    if (imgN != "" && localurlt == "")
                                    {
                                        if (color != "" && year != "" && imgname.Length > 10)
                                        {
                                            if (!Directory.Exists(localspModel + modelpath + "/" + year + "/" + color))
                                            {
                                                Directory.CreateDirectory(localspModel + modelpath + "/" + year + "/" + color);
                                            }
                                            if (!File.Exists(localspModel + modelpath + "/" + year + "/" + color + "/" + imgname))
                                            {
                                                using (WebClient downloader = new WebClient())
                                                {
                                                    string newurl1 = localspModel + modelpath + "/" + year + "/" + color + "/" + imgname;
                                                    downloader.DownloadFile(dlurl, @newurl1);
                                                    SqlCommand cmd = new SqlCommand("UPDATE dbo.BikeModelimagesJeff SET LocalUrl=@img WHERE Model=@model and Year=@year and color=@color", cs);
                                                    cmd.Parameters.AddWithValue("img", newurl1);
                                                    cmd.Parameters.AddWithValue("model", modelA);
                                                    cmd.Parameters.AddWithValue("year", year);
                                                    cmd.Parameters.AddWithValue("color", color);
                                                    cmd.ExecuteNonQuery();
                                                }
                                            }
                                            else
                                            {
                                                Console.WriteLine("It Exists");
                                            }

                                        }//----- if there is a color, year and model
                                        else if (color == "" && year != "" && imgname.Length > 10)
                                        {
                                            if (!Directory.Exists(localspModel + modelpath + "/" + year))
                                            {
                                                Directory.CreateDirectory(localspModel + modelpath + "/" + year);
                                            }
                                            if (!File.Exists(localspModel + modelpath + "/" + year + "/" + imgname))
                                            {
                                                using (WebClient downloader = new WebClient())
                                                {
                                                    string newurl1 = localspModel + modelpath + "/" + year + "/" + imgname;
                                                    downloader.DownloadFile(dlurl, @newurl1);
                                                    SqlCommand cmd = new SqlCommand("UPDATE dbo.BikeModelimagesJeff SET LocalUrl=@img WHERE Model=@model and Year=@year and color=Null", cs);
                                                    cmd.Parameters.AddWithValue("img", newurl1.ToString());
                                                    cmd.Parameters.AddWithValue("model", modelA);
                                                    cmd.Parameters.AddWithValue("year", year);
                                                    cmd.ExecuteNonQuery();
                                                }
                                            }
                                            else
                                            {
                                                Console.WriteLine("It Exists2");
                                            }
                                        }//------ else if there is only year and model
                                        else if (color == "" && year == "" && imgname.Length > 10)
                                        {
                                            if (!Directory.Exists(localspModel + modelpath))
                                            {
                                                Directory.CreateDirectory(localspModel + modelpath);
                                            }
                                            if (!File.Exists(localspModel + modelpath + "/" + imgname))
                                            {
                                                using (WebClient downloader = new WebClient())
                                                {
                                                    string newurl1 = localspModel + modelpath + "/" + imgname;
                                                    downloader.DownloadFile(dlurl, @newurl1);
                                                    SqlCommand cmd = new SqlCommand("UPDATE dbo.BikeModelimagesJeff SET LocalUrl=@img WHERE Model=@model", cs);
                                                    cmd.Parameters.AddWithValue("img", newurl1.ToString());
                                                    cmd.Parameters.AddWithValue("model", modelA);
                                                    cmd.ExecuteNonQuery();
                                                }
                                            }
                                            else
                                            {
                                                Console.WriteLine("It Exists3");
                                            }
                                        }//----- else if there is only model
                                        else
                                        {
                                            Console.WriteLine("model is empty");
                                        }

                                    }//----- end if imgN  not empty from bikem
                                }
                                

                            }//--end for loop in bikemodelimages

                        }//-----end else no img in capp_inventory


                    }//-----end for loop------//
                    //--------======= end db to folder mirroring =======------//

    */
                }
            }//---- end of using connection

            Console.ReadLine();
        }
       
    }
}
