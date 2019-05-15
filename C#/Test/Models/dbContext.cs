using System;
using System.Collections.Generic;
using System.Data.Entity;
using System.Linq;
using System.Web;

namespace Test.Models
{
    public class dbContext : DbContext
    {
        public dbContext() : base("name=dbcontext")
        {

        }

        protected override void OnModelCreating(DbModelBuilder modelBuilder)
        {
            base.OnModelCreating(modelBuilder);
        }
        public DbSet<Contact> Contacts { get; set; }

        public DbSet<ContactNumber> Numbers { get; set; }

        public DbSet<Emails> Emails { get; set; }

    }
}