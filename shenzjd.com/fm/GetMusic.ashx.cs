using System;
using System.Collections.Generic;
using System.IO;
using System.Linq;
using System.Net;
using System.Text;
using System.Web;

namespace WebApplication3
{
    /// <summary>
    /// GetMusic 的摘要说明
    /// </summary>
    public class GetMusic : IHttpHandler      
    {

       
        PostAndGet pg = new PostAndGet();
        public void ProcessRequest(HttpContext context) 
        {
              // string Songname= System.Web.HttpContext.Current.Request.QueryString["SongName"];
                  string Songname=  context.Request["SongName"].ToString();
                  string OffSet = context.Request["OffSet"].ToString();
                  Songname = context.Server.UrlEncode(Songname);
             
            string json= pg.HttpPost("http://music.163.com/api/search/pc", "s="+Songname+"&limit=30&type=1&offset="+OffSet);
            json = json.Replace("http://m", "http://p");
            context.Response.ContentType = "text/plain";
            context.Response.Write(json);
        }

        public bool IsReusable
        {
            get
            {
                return false;
            }
        }
    }
}