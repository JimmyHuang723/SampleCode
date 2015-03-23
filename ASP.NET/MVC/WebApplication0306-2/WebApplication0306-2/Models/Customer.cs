using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;

namespace WebApplication0306_2.Models
{
    public class Customer
    {
        private string _Code;
        private int _Id;
        private double _Amount;

        public string CustomerCode
        {
            set
            {
                _Code = value;
            }
            get
            {
                return _Code;
            }
        }

        public int Id
        {
            get
            {
                return _Id;
            }
            set
            {
                _Id = value;
            }
        }

        public double Amount
        {
            set
            {
                _Amount = value;
            }
            get
            {
                return _Amount;
            }
        }
    }
}