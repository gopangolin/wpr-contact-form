import React, { Component } from 'react';
import PropTypes from 'prop-types';

import fetchWP from '../utils/fetchWP';

export default class Admin extends Component {
  constructor(props) {
    super(props);

    // Set the default states
    this.state = {
      email: '',
      savedEmail: ''
    };

    this.fetchWP = new fetchWP({
      restURL: this.props.wpObject.api_url,
      restNonce: this.props.wpObject.api_nonce,
    });

    // Get the currently set email address from our /admin endpoint and update the email state accordingly
    this.getSetting();
  }

  getSetting = () => {
    this.fetchWP.get( 'admin' )
    .then(
      (json) => this.setState({
        email: json.value,
        savedEmail: json.value
      }),
      (err) => console.log( 'error', err )
    );
  };

  updateSetting = () => {
    this.fetchWP.post( 'admin', { email: this.state.email } )
    .then(
      (json) => this.processOkResponse(json, 'saved'),
      (err) => console.log('error', err)
    );
  }

  deleteSetting = () => {
    this.fetchWP.delete( 'admin' )
    .then(
      (json) => this.processOkResponse(json, 'deleted'),
      (err) => console.log('error', err)
    );
  }

  processOkResponse = (json, action) => {
    if (json.success) {
      this.setState({
        email: json.value,
        savedEmail: json.value,
      });
    } else {
      console.log(`Setting was not ${action}.`, json);
    }
  }

  updateInput = (event) => {
    this.setState({
      email: event.target.value,
    });
  }

  handleSave = (event) => {
    event.preventDefault();
    if ( this.state.email === this.state.savedEmail ) {
      console.log('Setting unchanged');
    } else {
      this.updateSetting();
    }
  }

  handleDelete = (event) => {
    event.preventDefault();
    this.deleteSetting();
  }

  render() {
    // Here we update the contents of the h1, and the label, type and value of the input.
    return (
      <div className="wrap">
        <form>
          <h1>Contact Form Settings</h1>
          
          <label>
          Notification Email:
            <input
              type="email"
              value={this.state.email}
              onChange={this.updateInput}
            />
          </label>

          <button
            id="save"
            className="button button-primary"
            onClick={this.handleSave}
          >Save</button>

          <button
            id="delete"
            className="button button-primary"
            onClick={this.handleDelete}
          >Delete</button>
        </form>
      </div>
    );
  }
}

Admin.propTypes = {
  wpObject: PropTypes.object
};