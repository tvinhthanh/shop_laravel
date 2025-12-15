@extends('layouts.admin')

@section('title', 'Khách hàng')
@section('page-title', 'Quản lý Khách hàng')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="bi bi-people"></i> Danh sách khách hàng</h5>
    </div>
    <div class="card-body">
        @if($customers->count() === 0)
            <p class="text-muted mb-0">Chưa có khách hàng nào.</p>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Tên</th>
                            <th>Email</th>
                            <th>Số đơn hàng</th>
                            <th>Tổng chi tiêu</th>
                            <th>Ngày đăng ký</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($customers as $customer)
                            <tr>
                                <td>{{ $customer->id }}</td>
                                <td>{{ $customer->name }}</td>
                                <td>{{ $customer->email }}</td>
                                <td>
                                    <span class="badge bg-primary">
                                        {{ $customer->orders_count }}
                                    </span>
                                </td>
                                <td>
                                    {{ number_format($customer->total_spent ?? 0, 0, ',', '.') }}đ
                                </td>
                                <td>{{ $customer->created_at->format('d/m/Y') }}</td>
                                <td class="text-end">
                                    <a href="{{ route('customers.show', $customer->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i> Xem
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $customers->links() }}
            </div>
        @endif
    </div>
</div>
@endsection


