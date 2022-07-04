package com.riya.canteenautomationapp.api;

import static com.riya.canteenautomationapp.utils.Constants.BASE_URL;

import com.google.gson.GsonBuilder;

import okhttp3.OkHttpClient;
import okhttp3.logging.HttpLoggingInterceptor;
import retrofit2.Retrofit;
import retrofit2.converter.gson.GsonConverterFactory;

public class ApiClient {

    public static ApiServices getApiService(){
        HttpLoggingInterceptor interceptorObj = new HttpLoggingInterceptor();
        interceptorObj.setLevel(HttpLoggingInterceptor.Level.BODY);

        OkHttpClient clientObj = new OkHttpClient.Builder().addInterceptor(interceptorObj).build();

        Retrofit retrofit = new Retrofit.Builder().baseUrl(BASE_URL)
                .addConverterFactory(GsonConverterFactory
                        .create(new GsonBuilder().create()))
                .client(clientObj).build();

        return retrofit.create(ApiServices.class);
    }
}
