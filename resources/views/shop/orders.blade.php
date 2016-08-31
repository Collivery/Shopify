@extends('layouts.app')
@section('title', 'Orders')
@section('content')
    <section class="full-width">
        <div class="card columns four has-sections">
            <a href="#"><div class="card-section">mds-shop3.myshopify.com</div></a>
            <hr>
            <a href="#"><div class="card-section">mds-shop2.myshopify.com</div></a>
        </div>
        <div class="card columns nine">
            <form action=""><div class="input-group"><input type="search" placeholder="Order number...."></div></form>
            <table class="results">
                <thead>
                <th>#</th>
                <th>Order number</th>
                <th>Waybill number</th>
                <th>Order status</th>
                <th>Shipping <b>ZAR</b></th>
                <th>Created</th>
                </thead>
                <tr>
                <td>1</td>
                <td><a href="#">#1023</a></td>
                <td><a href="https://collivery.co.za">203434</a></td>
                <td><span class="tag green">Accepted</span></td>
                <td>R 200</td>
                <td>2016-12-23</td>
                </tr><tr>
                <td>1</td>
                <td><a href="#">#1023</a></td>
                <td><a href="https://collivery.co.za">203434</a></td>
                <td><span class="tag red">Error</span></td>
                <td>R 200</td>
                <td>2016-12-23</td>
                </tr><tr>
                <td>1</td>
                <td><a href="#">#1023</a></td>
                <td><a href="https://collivery.co.za">203434</a></td>
                <td><span class="tag green">Accepted</span></td>
                <td>R 200</td>
                <td>2016-12-23</td>
                </tr><tr>
                <td>1</td>
                <td><a href="#">#1023</a></td>
                <td><a href="https://collivery.co.za">203434</a></td>
                <td><span class="tag red">Error</span></td>
                <td>R 200</td>
                <td>2016-12-23</td>
                </tr>
                </tbody>
            </table>
        </div>
    </section>
@endsection
