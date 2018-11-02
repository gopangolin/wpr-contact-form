import React, { Component } from 'react';
import PropTypes from 'prop-types';

import ContactForm from '../components/contactForm';

export default class Shortcode extends Component {
  render() {
    return (
      <div>
        <h1>{this.props.wpObject.title}</h1>
        <ContactForm wpObject={this.props.wpObject} />
      </div>
    );
  }
}

Shortcode.propTypes = {
  wpObject: PropTypes.object
};