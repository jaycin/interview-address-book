using System;
using System.Collections.Generic;
using System.Linq;
using System.Runtime.Serialization;
using System.Web;

namespace Test.Models.DTO
{
    [DataContract]
    public class ContactsDTO
    {
        [DataMember]
        public List<ContactDTO> Contacts { get; set; }

        [DataMember]
        public int Total { get; set; }
    }
}