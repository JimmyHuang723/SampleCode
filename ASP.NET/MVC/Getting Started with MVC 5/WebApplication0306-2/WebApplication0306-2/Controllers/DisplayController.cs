using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.Mvc;
using WebApplication0306_2.Models;

namespace WebApplication0306_2.Controllers
{
    public class DisplayController : Controller
    {
        // GET: Display
        public ActionResult Index()
        {
            Customer objCustomer = new Customer();
            objCustomer.Id = 12;
            objCustomer.CustomerCode = "0311";
            objCustomer.Amount = 90.34;
            return View("DisplayCustomer", objCustomer);
        }

        public string plainhtml(int id, string name)
        {
            //return "This is my <b>test</b> action...";
            return HttpUtility.HtmlEncode("Hello " + name + ", ID: " + id);
        }

        public ActionResult Fill()
        {
            return View("FillCustomer");
        }

        [HttpPost]
        public ViewResult Display()
        {
            Customer objCustomer = new Customer();
            objCustomer.Id = Convert.ToInt16(Request.Form["Id"].ToString());
            objCustomer.CustomerCode = Request.Form["CustomerCode"].ToString();
            objCustomer.Amount = Convert.ToDouble(Request.Form["Amount"].ToString()); ;
            return View("DisplayCustomer", objCustomer);
        }

    }
}