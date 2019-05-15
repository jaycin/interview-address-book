using System;
using System.Collections.Generic;
using System.Linq;
using System.Runtime.Serialization;
using System.Web;

namespace Test.Models.DTO
{
    [DataContract]
    public class ContactsRequestDTO
    {
        [DataMember]
        public int page { get; set; }
        [DataMember]
        public int limit { get; set; }
        [DataMember]
        public string search { get; set; }
    }
}