using System;
using System.Collections.Generic;
using System.Linq;
using System.Security.Cryptography;
using System.Web;

namespace WebApplication3
{
    /// <summary>
    /// GetAddress 的摘要说明
    /// </summary>
    public class GetAddress : IHttpHandler
    {
        PostAndGet pg = new PostAndGet();
        public void ProcessRequest(HttpContext context)
        {
            // string Songname= System.Web.HttpContext.Current.Request.QueryString["SongName"];
            string id = context.Request["id"].ToString();
            string json = pg.HttpGet("http://music.163.com/api/album/"+id+"/", "");
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

        public string encrypted(string id)
        {
            string key = "3go8&$8*3*3h0k(2)2";
            int key_len = key.Length;
            int length = id.Length;
            char[] ids = id.ToArray();
            //StringBuilder sb = new StringBuilder();
            for (int i = 0; i < length; i++)
            {
                ids[i] = (char)(id[i] ^ key[i % key_len]);

                //sb.Append((id[i] ^ key[i % key_len]).ToString());
            }
            //转换成base64
            byte[] byteArray = System.Text.Encoding.Default.GetBytes(ids);
            MD5 md5 = new MD5CryptoServiceProvider();
            byte[] output = md5.ComputeHash(byteArray);

            var base64String = Convert.ToBase64String(output);

            base64String = base64String.Replace("/", "_");
            base64String = base64String.Replace("+", "-");

            return base64String;


        }
    }
}