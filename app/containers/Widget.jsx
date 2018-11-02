import React, { Component } from 'react';
import PropTypes from 'prop-types';

import ContactForm from '../components/contactForm';

export default class Widget extends Component {
  render() {
    return (
      <div>
        <section className="widget">
          <h1 className="widget-title">{this.props.wpObject.title}</h1>
          <ContactForm wpObject={this.props.wpObject} />
        </section>
      </div>
    );  
  }
}

Widget.propTypes = {
  wpObject: PropTypes.object
};