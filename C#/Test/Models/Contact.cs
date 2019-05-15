using System;
using System.Collections.Generic;
using System.ComponentModel.DataAnnotations;
using System.Linq;
using System.Runtime.Serialization;
using System.Web;

namespace Test.Models
{
    [DataContract]
    public class Contact
    {
        [Key]
        [DataMember]
        public long ContactId { get; set; }


        [DataMember]
        public string FirstName { get; set; }
        [DataMember]
        public string LastName { get; set; }

        [DataMember]
        public int isDeleted { get; set;}
        
        [DataMember]
        public virtual ICollection<ContactNumber> ContactNumbers { get; set; }
        [DataMember]
        public virtual ICollection<Emails> Emails { get; set; }

    }
}