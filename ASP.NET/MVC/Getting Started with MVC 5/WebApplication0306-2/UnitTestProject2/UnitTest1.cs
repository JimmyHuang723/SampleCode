using System;
using Microsoft.VisualStudio.TestTools.UnitTesting;
using WebApplication0306_2.Controllers;

namespace UnitTestProject2
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
