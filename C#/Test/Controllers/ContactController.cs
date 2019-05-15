using System;
using System.Collections.Generic;
using System.Linq;
using System.Net;
using System.Web.Http;
using Test.Models;
using Test.Models.DTO;

namespace Test.Controllers
{
    public class ContactController : ApiController
    {
        private dbContext context { get; set; }
        // GET api/Contact/GetContacts
        [Route("api/Contact/GetContacts")]
        [HttpGet]
        public ContactsDTO GetContacts([FromUri] ContactsRequestDTO dto)
        {
            ContactsDTO contactDto = new ContactsDTO();
            contactDto.Contacts = new List<ContactDTO>();
            using (context = new dbContext())
            {
                context.Contacts.
                    Where(i=>i.isDeleted != 1).
                    OrderBy(i=>i.ContactId).
                    Skip((dto.page*dto.limit)).
                    Take(dto.limit).ToList().
                    ForEach(e=>contactDto.Contacts.Add(new ContactDTO(e)));
                contactDto.Total = context.Contacts.Where(i => i.isDeleted != 1).Count();
            }
            return contactDto;
        }

        // GET api/Contact/SearchContact
        [Route("api/Contact/SearchContacts")]
        [HttpGet]
        public ContactsDTO SearchContacts([FromUri] ContactsRequestDTO dto)
        {
            ContactsDTO contactDto =  new ContactsDTO();
            contactDto.Contacts = new List<ContactDTO>();
            using (context = new dbContext())
            {
                context.Contacts
                    .Where(i => i.isDeleted != 1 && i.FirstName.Contains(dto.search) 
                    || i.LastName.Contains(dto.search)
                    || i.ContactNumbers.Any(y => y.Number.Contains(dto.search))
                    || i.Emails.Any(y=>y.Email.Contains(dto.search)))
                    .OrderBy(i => i.ContactId)
                    .Skip((dto.page * dto.limit)).Take(dto.limit).ToList().
                    ForEach(e=> contactDto.Contacts.Add(new ContactDTO(e)));
                contactDto.Total = context.Contacts.Where(i => i.isDeleted != 1).Count();

            }
            return contactDto;
        }

        // POST api/Contact/Upsert
        
        [HttpPost]
        public void post([FromBody]ContactDTO c)
        {
            using (context = new dbContext())
            {
                if(c.ContactId == 0)
                {
                    Contact contact = new Contact() { FirstName = c.FirstName, LastName = c.LastName };
                    List<Emails> emails = new List<Emails>();
                     c.Emails.Split(',').ToList().ForEach(e=> emails.Add(new Emails() { Email = e}));
                    contact.Emails = emails;

                    List<ContactNumber> contactNumbers = new List<ContactNumber>();
                    c.ContactNumbers.Split(',').ToList().ForEach(e => contactNumbers.Add(new ContactNumber() { Number = e }));
                    contact.ContactNumbers = contactNumbers;

                    context.Contacts.Add(contact); 
                }
                else
                {
                    Contact contact = context.Contacts.Where(i => i.ContactId == c.ContactId).FirstOrDefault();
                    contact.FirstName = c.FirstName;
                    contact.LastName = c.LastName;
                    context.Numbers.RemoveRange(contact.ContactNumbers);
                    contact.ContactNumbers = new List<ContactNumber>();
                    c.ContactNumbers.Split(',').ToList().ForEach(e => contact.ContactNumbers.Add(new ContactNumber() { Number = e }));

                    context.Emails.RemoveRange(contact.Emails);
                    contact.Emails = new List<Emails>();
                    c.Emails.Split(',').ToList().ForEach(e => contact.Emails.Add(new Emails() { Email = e }));

                }
                context.SaveChanges();
            }
        }


        // DELETE api/Contact/5
        
        [HttpDelete]
        public void Delete([FromBody]int id)
        {
            using (context = new dbContext())
            {
                Contact c = context.Contacts.Where(i => i.ContactId == id).FirstOrDefault();
                c.isDeleted = 1;
                context.SaveChanges();
            }
        }
    }
}
