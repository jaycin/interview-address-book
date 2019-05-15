using System;
using System.Collections.Generic;
using System.Linq;
using System.Runtime.Serialization;
using System.Web;

namespace Test.Models.DTO
{
    [DataContract]
    public class ContactDTO
    {

        public ContactDTO(Contact c)
        {
            this.ContactId = c.ContactId;
            this.FirstName = c.FirstName;
            this.LastName = c.LastName;
            this.Emails = String.Join(",",c.Emails.Select(i => i.Email).ToArray());
            this.ContactNumbers = String.Join(",", c.ContactNumbers.Select(i => i.Number).ToArray());
        }

        public ContactDTO()
        {

        }

        [DataMember]
        public long ContactId { get; set; }


        [DataMember]
        public string FirstName { get; set; }
        [DataMember]
        public string LastName { get; set; }

        [DataMember]
        public string ContactNumbers { get; set; }

        [DataMember]
        public string Emails { get; set; }

        

    }
}