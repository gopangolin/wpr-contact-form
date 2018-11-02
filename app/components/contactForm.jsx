import React from 'react';

export default class ContactForm extends React.Component {
 
  render() {    
    return (
      <div>
        <form>
          <label>
          Name:
            <input
              type="text"
              name="name"
            />
          </label>

          <label>
          Email:
            <input
              type="email"
              name="email"
            />
          </label>

          <label>
          Message:
            <textarea 
              name="message"
            />
          </label>

          <button
            id="submit"
            className="button button-primary"
          >Submit</button>

        </form>;
      </div>
    );
  }
}