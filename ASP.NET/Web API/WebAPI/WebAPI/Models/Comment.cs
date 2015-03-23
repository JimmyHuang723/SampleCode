using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;

namespace WebAPI.Models
{
    public class Comment
    {
        public int ID { get; set; }
        public string Author { get; set; }
        public string Text { get; set; }
    }
}