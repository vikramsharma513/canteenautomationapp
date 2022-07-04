package com.riya.canteenautomationapp.responses;

import com.google.gson.annotations.Expose;
import com.google.gson.annotations.SerializedName;

import java.util.List;

public class OrderResponseData {


    @SerializedName("order_id")
    @Expose
    private Integer orderId;
    @SerializedName("customerId")
    @Expose
    private Integer customerId;
    @SerializedName("name")
    @Expose
    private String name;
    @SerializedName("phoneNum")
    @Expose
    private String phoneNum;
    @SerializedName("order_date")
    @Expose
    private String orderDate;
    @SerializedName("pay_method")
    @Expose
    private String payMethod;
    @SerializedName("status")
    @Expose
    private Integer status;
    @SerializedName("pay_status")
    @Expose
    private Integer payStatus;

    public Integer getOrderId() {
        return orderId;
    }

    public void setOrderId(Integer orderId) {
        this.orderId = orderId;
    }

    public Integer getCustomerId() {
        return customerId;
    }

    public void setCustomerId(Integer customerId) {
        this.customerId = customerId;
    }

    public String getName() {
        return name;
    }

    public void setName(String name) {
        this.name = name;
    }

    public String getPhoneNum() {
        return phoneNum;
    }

    public void setPhoneNum(String phoneNum) {
        this.phoneNum = phoneNum;
    }

    public String getOrderDate() {
        return orderDate;
    }

    public void setOrderDate(String orderDate) {
        this.orderDate = orderDate;
    }

    public String getPayMethod() {
        return payMethod;
    }

    public void setPayMethod(String payMethod) {
        this.payMethod = payMethod;
    }

    public Integer getStatus() {
        return status;
    }

    public void setStatus(Integer status) {
        this.status = status;
    }

    public Integer getPayStatus() {
        return payStatus;
    }

    public void setPayStatus(Integer payStatus) {
        this.payStatus = payStatus;
    }
}
