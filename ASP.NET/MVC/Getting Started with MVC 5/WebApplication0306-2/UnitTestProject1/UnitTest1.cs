using System;
//using System.Web;
//using System.Web.Mvc;
using Microsoft.VisualStudio.TestTools.UnitTesting;
using WebApplication0306_2.Controllers;

namespace UnitTestProject1
{
    [TestClass]
    public class UnitTest1
    {
        [TestMethod]
        public void TestMethod1()
        {
        }

        [TestMethod]
        public void DisplayCustomer()
        {
            DisplayController obj = new DisplayController();
            var varresult = obj.Display();
            //Assert.AreEqual("DisplayCustomer", varresult.ViewName);
        }

    }
}
