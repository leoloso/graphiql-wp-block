import React, { Component } from "react";
import GraphiQL from 'graphiql';
import fetch from 'isomorphic-fetch';
import 'graphiql/graphiql.css';
import { __ } from '@wordpress/i18n';

class EditBlock extends Component {

    graphQLFetcher(graphQLParams) {
        return fetch(window.location.origin + '/api/graphql', {
            method: 'post',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(graphQLParams),
        }).then(response => response.json());
    }

    render() {
        return (
            <GraphiQL
                style={{ height: '80vh' }}
                fetcher={ this.graphQLFetcher }
            />
        );
    }
}

export default EditBlock;
