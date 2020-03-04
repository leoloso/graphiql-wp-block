import React, { Component } from "react";
import { __ } from '@wordpress/i18n';

class EditBlock extends Component {

  constructor(props) {
    super(props);
    this.setAttributes = props.setAttributes;
    this.isSelected = props.isSelected;
    this.className = props.className;
	this.attributes = props.attributes;
  }

  // Toggle a setting when the user clicks the button
  toggleSetting() {
    this.setAttributes({ mySetting: ! mySetting });
  }

  render() {
    // Simplify access to attributes
	const { content, mySetting } = this.attributes;
	return (
		<p className={ this.className }>
			{ __(
				'GraphiQL – hello from the editor, sarongasonga!',
				'leoloso'
			) }
		</p>
	);
    // return (
    //   <div className={ this.className }>
    //     { content }
    //     { this.isSelected &&
    //       <button onClick={() => this.toggleSetting }>Toggle setting</button>
    //     }
    //   </div>
    // );
  }
}

export default EditBlock;
