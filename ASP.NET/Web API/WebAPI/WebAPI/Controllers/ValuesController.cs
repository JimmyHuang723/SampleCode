using System;
using System.Collections.Generic;
using System.Linq;
using System.Net;
using System.Net.Http;
using System.Web.Http;
using WebAPI.Models;
using System.Web.Http.Cors;

namespace WebAPI.Controllers
{
    [EnableCors(origins: "*", headers: "*", methods: "*")]
    public class ValuesController : ApiController
    {
        // GET api/values
        public IEnumerable<string> Get()
        {
            return new string[] { "value1", "value2" };
        }

        // GET api/values/5
        public string Get(int id)
        {
            return "get" + id;
        }

        public string PostComment(int id, Comment comment)
        {
            var response = new HttpResponseMessage();
            //response.Headers.Location = new Uri(Request.RequestUri, "/api/comments/" + comment.ID.ToString());
            
            return id.ToString() + comment.Author;
        }

        /*
        // POST api/values
        public string Post([FromBody]string value)
        {
         
            return "post" + value;
        }
        */

        // PUT api/values/5
        public string Put(int id, [FromBody]string value)
        {
            return "put";
        }

        // DELETE api/values/5
        public string Delete(int id)
        {
            return "delete";
        }
    }
}
