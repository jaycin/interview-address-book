namespace Test.Migrations
{
    using System;
    using System.Data.Entity.Migrations;
    
    public partial class test : DbMigration
    {
        public override void Up()
        {
            CreateTable(
                "dbo.Contacts",
                c => new
                    {
                        ContactId = c.Long(nullable: false, identity: true),
                        FirstName = c.String(),
                        LastName = c.String(),
                        Email = c.String(),
                    })
                .PrimaryKey(t => t.ContactId);
            
            CreateTable(
                "dbo.ContactNumbers",
                c => new
                    {
                        ContactNumberID = c.Long(nullable: false, identity: true),
                        Type = c.Int(nullable: false),
                        Number = c.String(),
                        Contact_ContactId = c.Long(),
                    })
                .PrimaryKey(t => t.ContactNumberID)
                .ForeignKey("dbo.Contacts", t => t.Contact_ContactId)
                .Index(t => t.Contact_ContactId);
            
        }
        
        public override void Down()
        {
            DropForeignKey("dbo.ContactNumbers", "Contact_ContactId", "dbo.Contacts");
            DropIndex("dbo.ContactNumbers", new[] { "Contact_ContactId" });
            DropTable("dbo.ContactNumbers");
            DropTable("dbo.Contacts");
        }
    }
}
