import React, { Component } from 'react';
import GraphiQL from 'graphiql';
import fetch from 'isomorphic-fetch';
import 'graphiql/graphiql.css';
import './style.scss';

class EditBlock extends Component {
	constructor( props ) {
		super( props );
		this.props = props;
		// this.className = props.className;
		// this.content = props.attributes.content;
		// this.setAttributes = props.setAttributes;
	}

	graphQLFetcher( graphQLParams ) {
		return fetch( window.location.origin + '/api/graphql', {
			method: 'post',
			headers: { 'Content-Type': 'application/json' },
			body: JSON.stringify( graphQLParams ),
		} ).then( ( response ) => response.json() );
	}

	render() {
		const { attributes: { content }, setAttributes, className } = this.props;
		const onEditQuery = ( newContent ) => {
            setAttributes( { content: newContent } );
        };
		return (
			<div className={ className }>
				<GraphiQL
					fetcher={ this.graphQLFetcher }
					query={ content }
					onEditQuery={ onEditQuery }
				/>
			</div>
		);
	}
}

export default EditBlock;
