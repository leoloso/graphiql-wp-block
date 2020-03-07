import React, { Component } from "react";
import GraphiQL from 'graphiql';
import fetch from 'isomorphic-fetch';
import 'graphiql/graphiql.css';
import './style.scss';

class EditBlock extends Component {

    constructor(props) {
        super(props);
        this.className = props.className
    }

    graphQLFetcher(graphQLParams) {
        return fetch(window.location.origin + '/api/graphql', {
            method: 'post',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(graphQLParams),
        }).then(response => response.json());
    }

    render() {
        return (
            <div className = {this.className}>
                <GraphiQL
                    fetcher={ this.graphQLFetcher }
                />
            </div>
        );
    }
}

export default EditBlock;
