import React, { Component } from "react";
import { Row, Col } from "reactstrap";
import "../css/UserHomePage.css";
import CustomNavbar from "./Navbar";
import BooksTable from "./BooksTable";
import CustomPagination from "./pagination";
import { Redirect } from "react-router-dom";
import axios from "axios";
import {UserContext} from './UserContext';

class UserHomePage extends Component {
  
    state = {
      isOpen: false,
      activePage: 1,
      itemsCount: 1,
      shelf: "all",
      myOrders: []
    };
  

  componentDidMount() {
    // const token = localStorage.token;
    // if (token) {
    //   const conf = {
    //     params: {
    //       page: `${this.state.activePage}`,
    //       mode: `${this.state.shelf}`
    //     },
    //     headers: {
    //       "x-auth": token
    //     }
    //   };

    //   axios
    //     .get(
    //       `/api/users/current?page=${this.state.activePage}?mode=${
    //       this.state.shelf
    //       }`,
    //       conf
    //     )
    //     .then(res => {
    //       console.log(res.data);
    //       this.setState({
    //         books: res.data.books,
    //         itemsCount: res.data.count
    //       });
    //     })
    //     .catch(err => console.log(err));
    // }
  }

  // test = setInterval(() => {
  //   alert(this.state.books[0].rate)
  // }, 5000);

  sendRequestShelf = shelf => {
    const token = localStorage.token;
    if (token) {
      const conf = {
        params: {
          page: 1,
          mode: `${shelf}`
        },
        headers: {
          "x-auth": token
        }
      };
      axios
        .get(`/api/users/current`, conf)
        .then(res => {
          console.log(conf.params.mode, res.data.books);

          this.setState({
            books: res.data.books,
            activePage: 1,
            itemsCount: res.data.count
          });
        })
        .catch(err => console.log(err));
    }
  };

  displayAllBooks = () => {
    if (this.state.shelf !== "all") {
      this.setState({
        shelf: "all",
        activePage: 1
      });
      this.sendRequestShelf("all");
    }
  };
  displayReadBooks = () => {
    if (this.state.shelf !== "read") {
      this.setState({
        shelf: "read",
        activePage: 1
      });
      this.sendRequestShelf("read");
    }
  };
  displayCurrentlyReadingBooks = () => {
    if (this.state.shelf !== "current") {
      this.setState({
        shelf: "current",
        activePage: 1
      });
      this.sendRequestShelf("current");
    }
  };
  displayToReadBooks = () => {
    if (this.state.shelf !== "toRead") {
      this.setState({
        shelf: "toRead",
        activePage: 1
      });
      this.sendRequestShelf("toRead");
    }
  };

  handelPagination = pageNum => {
    const token = localStorage.token;
    if (token) {
      const conf = {
        params: {
          page: `${pageNum}`,
          mode: `${this.state.shelf}`
        },
        headers: {
          "x-auth": token
        }
      };
      axios
        .get(`/api/users/current`, conf)
        .then(res => {
          this.setState({
            books: res.data.books,
            activePage: pageNum
          });
        })
        .catch(err => console.log(err));
    }
  };

  setMyOrders = (myOrders) =>{
    this.setState({
      myOrders,
    })
  }
  
  render() {
    return ( 
    // localStorage.token ? (
      
      <UserContext.Provider >


      <div>
        <CustomNavbar />
        <Row className="homeBody">
          <Col xs="3">
            <div className="sideMenu">
              <ul>
                <li>
                  <h6 link="#" onClick={this.displayAllBooks}>
                    All books
                  </h6>
                  <hr />
                </li>
                <li>
                  <h6 onClick={this.displayReadBooks}>Read</h6>
                  <hr />
                </li>
                <li>
                  <h6 onClick={this.displayCurrentlyReadingBooks}>
                    Currently reading
                  </h6>
                  <hr />
                </li>
                <li>
                  <h6 onClick={this.displayToReadBooks}>Want to read</h6>
                  <hr />
                </li>
              </ul>
            </div>
          </Col>
          <Col xs="9">
            <Row className="rightMenu">
              <Col>
                <BooksTable books={this.state.books} shelf={this.state.shelf} />
                <CustomPagination
                  chunk={5}
                  max={this.state.itemsCount}
                  activePage={this.state.activePage}
                  change={this.handelPagination}
                />
              </Col>
            </Row>
          </Col>
        </Row>
      </div>
      

      </UserContext.Provider>

    // ) : (
    //     <Redirect to={{ pathname: "/", state: { from: this.props.location } }} />
    //   );
  
    )}
}

export default UserHomePage;
