namespace Test.Migrations
{
    using System;
    using System.Data.Entity.Migrations;
    
    public partial class test1 : DbMigration
    {
        public override void Up()
        {
            CreateTable(
                "dbo.Emails",
                c => new
                    {
                        ID = c.Long(nullable: false, identity: true),
                        Email = c.String(),
                        Contact_ContactId = c.Long(),
                    })
                .PrimaryKey(t => t.ID)
                .ForeignKey("dbo.Contacts", t => t.Contact_ContactId)
                .Index(t => t.Contact_ContactId);
            
        }
        
        public override void Down()
        {
            DropForeignKey("dbo.Emails", "Contact_ContactId", "dbo.Contacts");
            DropIndex("dbo.Emails", new[] { "Contact_ContactId" });
            DropTable("dbo.Emails");
        }
    }
}
