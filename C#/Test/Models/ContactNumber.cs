using System.ComponentModel.DataAnnotations;
using System.Runtime.Serialization;

namespace Test.Models
{
   [DataContract]
    public class ContactNumber
    {
        [Key]
        [DataMember]
        public long ContactNumberID { get; set; }

        [DataMember]
        public ContactType Type { get; set; }

        [DataMember]
        public string Number { get; set; }

    }
}