using System.ComponentModel.DataAnnotations;
using System.Runtime.Serialization;

namespace Test.Models
{
    [DataContract]
    public class Emails
    {
        [Key]
        [DataMember]
        public long ID { get; set; }

        [DataMember]
        public string Email { get; set; }
    }
}