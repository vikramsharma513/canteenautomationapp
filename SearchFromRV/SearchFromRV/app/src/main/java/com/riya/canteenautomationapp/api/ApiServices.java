package com.riya.canteenautomationapp.api;

import com.riya.canteenautomationapp.responses.CategoryResponse;
import com.riya.canteenautomationapp.responses.CustomerLoginResponse;
import com.riya.canteenautomationapp.responses.CustomerRegisterResponse;
import com.riya.canteenautomationapp.responses.FoodItemResponse;

import retrofit2.Call;
import retrofit2.http.Field;
import retrofit2.http.FormUrlEncoded;
import retrofit2.http.Header;
import retrofit2.http.POST;

public interface ApiServices {
    @FormUrlEncoded
    @POST("/Backend/myApi/v1/loginCustomer")
    Call<CustomerLoginResponse> loginCustomer(@Field("email") String email,
                                              @Field("password") String password);

    @FormUrlEncoded
    @POST("/Backend/myApi/v1/registerCustomer")
    Call<CustomerRegisterResponse> registerCustomer(@Field("name") String name,
                                                    @Field("email") String email,
                                                    @Field("password") String password,
                                                    @Field("phoneNum") String phoneNum);

    @POST("/Backend/myApi/v1/view_categories")
    Call<CategoryResponse> view_categories(@Header("Authorization") String apiKey);


    @POST("/Backend/myApi/v1/view_items")
    Call<FoodItemResponse> check_items(@Header("Authorization") String apiKey);

}