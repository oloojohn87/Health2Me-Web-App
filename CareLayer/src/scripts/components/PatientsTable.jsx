/*
 *  Author:         Bruno Lima
 *  Date Created:   June 19, 2015
 *  Description:    Defines the PatientsTable React component, which displays a doctor's accessible patients
 */

var React = require('react/addons');
var Reflux = require('reflux');
var Radium = require('radium');

var PatientsStore = require('../stores/PatientsStore');

var ReactCSSTransitionGroup = React.addons.CSSTransitionGroup;

var row_height = 30;
var even_color = '#FCFCFC';
var odd_color = '#F2F2F2';

var PatientsTable = React.createClass(
{
    mixins: [Reflux.connect(PatientsStore, 'patients')],
    expand: function(i)
    {
        var patients = this.state.patients;
        patients[i].expanded = true;
        this.setState({patients: patients});
    },
    collapse: function(i)
    {
        var patients = this.state.patients;
        patients[i].expanded = false;
        this.setState({patients: patients});
    },
    render: function() 
    {
        var patients = this.state.patients.map(function(patient, i)
        {
            return (
                <div>
                    <div style={[styles.patients_row_styles, i % 2 == 1 && styles.odd_row]}>
                    <button style={patient.expanded ? styles.collapse_button : styles.expand_button} onClick={patient.expanded ? this.collapse.bind(this, i) : this.expand.bind(this, i)}>
                            {patient.expanded ? '-' : '+'}
                        </button>
                        {patient.Name + ' ' + patient.Surname}
                    </div>
                    <ReactCSSTransitionGroup transitionName="expand" component="div">
                    {
                        patient.expanded &&
                        <div key={'expanded_row_' + i} style={[styles.expanded_row_styles, {backgroundColor: (i % 2 == 1 ? odd_color : even_color)}]}></div>
                    }
                    </ReactCSSTransitionGroup>
                </div>
            );
        }.bind(this));
        return (
            <div style={[styles.patients_table_styles, {height: this.props.height + 'px'}]}>
                {patients}
            </div>
        );
    }
});

PatientsTable = Radium(PatientsTable);

var styles = {
    patients_table_styles: {
        width: '100%',
        overflowX: 'hidden',
        overflowY: 'scroll',
        background: 'linear-gradient(to bottom, ' + even_color + ', ' + even_color + ' 50%, ' + odd_color + ' 50%, ' + odd_color + ')',
        backgroundSize: '100% ' + (row_height * 2).toString() + 'px'
    },
    patients_row_styles: {
        width: '96%',
        height: (row_height - 7).toString() + 'px',
        paddingTop: '7px',
        paddingLeft: '3%',
        paddingRight: '1%',
        backgroundColor: even_color,
        fontFamily: "'Helvetica Neue', 'Helvetica', sans-serif",
        color: '#555'
    },
    odd_row: {
        backgroundColor: odd_color
    },
    expand_button: {
        height: '20px',
        width: '20px',
        borderRadius: '20px',
        backgroundColor: '#54BC00',
        color: '#FFF',
        border: '0px solid #FFF',
        outline: 'none',
        cursor: 'pointer',
        float: 'right',
        fontSize: '14px',
        paddingTop: '0px'
    },
    collapse_button: {
        height: '20px',
        width: '20px',
        borderRadius: '20px',
        backgroundColor: '#FF0000',
        color: '#FFF',
        border: '0px solid #FFF',
        outline: 'none',
        cursor: 'pointer',
        float: 'right',
        fontSize: '14px',
        paddingTop: '0px'
    },
    expanded_row_styles: { 
        width: '100%', 
        height: '120px'
    }
};

module.exports = PatientsTable;