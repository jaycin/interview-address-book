namespace Test.Migrations
{
    using System;
    using System.Data.Entity.Migrations;
    
    public partial class test2 : DbMigration
    {
        public override void Up()
        {
            AddColumn("dbo.Contacts", "isDeleted", c => c.Int(nullable: false));
            DropColumn("dbo.Contacts", "Email");
        }
        
        public override void Down()
        {
            AddColumn("dbo.Contacts", "Email", c => c.String());
            DropColumn("dbo.Contacts", "isDeleted");
        }
    }
}
