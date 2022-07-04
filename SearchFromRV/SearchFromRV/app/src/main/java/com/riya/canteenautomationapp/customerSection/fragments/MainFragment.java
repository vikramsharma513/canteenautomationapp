package com.riya.canteenautomationapp.customerSection.fragments;

import android.content.Intent;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.SearchView;
import android.widget.Spinner;
import android.widget.Toast;

import androidx.fragment.app.Fragment;
import androidx.recyclerview.widget.GridLayoutManager;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;
import androidx.swiperefreshlayout.widget.SwipeRefreshLayout;

import com.riya.canteenautomationapp.R;
import com.riya.canteenautomationapp.api.ApiClient;
import com.riya.canteenautomationapp.customerSection.ItemDisplayActivity;
import com.riya.canteenautomationapp.responses.CategoryResponse;
import com.riya.canteenautomationapp.responses.CategoryResponseData;
import com.riya.canteenautomationapp.responses.FoodItemResponse;
import com.riya.canteenautomationapp.responses.FoodItemResponseData;
import com.riya.canteenautomationapp.utils.CategoryAdapter;
import com.riya.canteenautomationapp.utils.Constants;
import com.riya.canteenautomationapp.utils.FeedAdapter;
import com.riya.canteenautomationapp.utils.SharedPreferencesUtils;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.Collections;
import java.util.Comparator;
import java.util.List;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class MainFragment extends Fragment implements FeedAdapter.ClickListeners, CategoryAdapter.ClickListener {

    RecyclerView list,categoryRV;
    List<FoodItemResponseData> foodlist;
    List<CategoryResponseData> categoryResponseDataList;
    FeedAdapter adapter;
    CategoryAdapter categoryAdapter;
    SearchView searchView;
    SwipeRefreshLayout swipeToRefresh;
    Spinner sp;
    String data[]={"Sort By","Price","Name"};
    ArrayList spinnerList= new ArrayList(Arrays.asList(data));

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        // Inflate the layout for this fragment
        View view= inflater.inflate(R.layout.fragment_main, container, false);
        foodlist= new ArrayList<>();
        list=view.findViewById(R.id.foodlist);
        categoryRV=view.findViewById(R.id.catgoryRV);
        searchView=view.findViewById(R.id.searchView);
        swipeToRefresh=view.findViewById(R.id.swipeToRefresh);
        sp=view.findViewById(R.id.spinner);

        swipeToRefresh.setOnRefreshListener(new SwipeRefreshLayout.OnRefreshListener() {
            @Override
            public void onRefresh() {
                serverCall();
                spinnerAddData();
            }
        });
        serverCall();
        searchListener();
        spinnerAddData();
        return view;
    }
// sorting food list by price and name
    private void spinnerAddData() {
        ArrayAdapter<String> spinnerAdapter= new ArrayAdapter(getContext(),R.layout.support_simple_spinner_dropdown_item, spinnerList);
        sp.setAdapter(spinnerAdapter);
        sp.setOnItemSelectedListener(new AdapterView.OnItemSelectedListener() {
            @Override
            public void onItemSelected(AdapterView<?> parent, View view, int position, long id) {
                String item = parent.getItemAtPosition(position).toString();
                if(item.equals("Price")){
                    Collections.sort(foodlist, new Comparator<FoodItemResponseData>() {
                        @Override
                        public int compare(FoodItemResponseData o1, FoodItemResponseData o2) {
                            return o1.getPrice().compareTo(o2.getPrice());
                        }
                    });
                    adapter.notifyDataSetChanged();
                }
                else if(item.equals("Name") ){
                    Collections.sort(foodlist, new Comparator<FoodItemResponseData>() {
                        @Override
                        public int compare(FoodItemResponseData o1, FoodItemResponseData o2) {
                            return o1.getFoodName().toLowerCase().compareTo(o2.getFoodName().toLowerCase());
                        }
                    });
                    adapter.notifyDataSetChanged();
                }
            }
            @Override
            public void onNothingSelected(AdapterView<?> parent) {

            }
        });
    }

    // for searching food item
    private void searchListener() {
        searchView.setOnQueryTextListener(new SearchView.OnQueryTextListener() {
            @Override
            public boolean onQueryTextSubmit(String query) {
                return false;
            }

            @Override
            public boolean onQueryTextChange(String newText) {
                adapter.getFilter().filter(newText);
                return false;
            }
        });
    }
//////search end

    private void filterList(String selectedCategory){
        adapter.getFilter().filter(selectedCategory);
    }

    private void serverCall(){
        String apiKey= SharedPreferencesUtils.getStringPreference(getContext(), Constants.API_KEY_KEY);
        if(apiKey!=null){
            categoryDisplay();
            foodItemsDisplay();
        }

    }

    private void categoryDisplay() {

        Call<CategoryResponse> categoryResponseCall=ApiClient.getApiService().view_categories(
                SharedPreferencesUtils.getStringPreference(getContext(),Constants.API_KEY_KEY));
        categoryResponseCall.enqueue(new Callback<CategoryResponse>() {
            @Override
            public void onResponse(Call<CategoryResponse> call, Response<CategoryResponse> response) {
                if(response.isSuccessful()){
                    if(!response.body().getError()){
                        if(response.body().getData() !=null)
                            setCategoryRecyclerView(response.body().getData());
                    }
                }
                else{
                    Toast.makeText(getContext(),"cant call server", Toast.LENGTH_LONG).show();
                }
            }

            @Override
            public void onFailure(Call<CategoryResponse> call, Throwable t) {

            }
        });
    }

    private void setCategoryRecyclerView(List<CategoryResponseData> categoryResponseData) {
        categoryResponseDataList=categoryResponseData;
        categoryRV.setHasFixedSize(true);
        categoryRV.setLayoutManager(new LinearLayoutManager(getContext(), LinearLayoutManager.HORIZONTAL, false) );
        categoryAdapter= new CategoryAdapter(categoryResponseDataList,getContext());
        categoryAdapter.setClickListeners(this::onCategoryClicked);
        categoryRV.setAdapter(categoryAdapter);
        swipeToRefresh.setRefreshing(false);
    }

    private void foodItemsDisplay(){
        Call<FoodItemResponse> responseCall = ApiClient.getApiService().check_items(
                SharedPreferencesUtils.getStringPreference(getContext(), Constants.API_KEY_KEY));
        responseCall.enqueue(new Callback<FoodItemResponse>() {
            @Override
            public void onResponse(Call<FoodItemResponse> call, Response<FoodItemResponse> response) {
                if(response.isSuccessful()){
                    if (!response.body().getError()) {
                        if(response.body().getData() !=null)
                            setRecyclerView(response.body().getData());
                        else
                            showNoItemsLayout();
                    }
                }else{
                    Toast.makeText(getContext(),"cant call server", Toast.LENGTH_LONG).show();
                }
            }
            @Override
            public void onFailure(Call<FoodItemResponse> call, Throwable t) {

            }
        });
    }

    private void setRecyclerView(List<FoodItemResponseData> responseData){
        foodlist=responseData;
        list.setHasFixedSize(true);
        list.setLayoutManager(new GridLayoutManager(getContext(), 2));
        adapter=new FeedAdapter(foodlist,getContext());
        adapter.setClickListeners(this);
        list.setAdapter(adapter);
        swipeToRefresh.setRefreshing(false);
    }

    private void showNoItemsLayout() {
        Toast.makeText(getActivity(),"No food items to show!",Toast.LENGTH_SHORT).show();
    }

    @Override
    public void onItemClicked(int position) {
        FoodItemResponseData responseData= foodlist.get(position);
        Intent intent= new Intent(getActivity(), ItemDisplayActivity.class);
        intent.putExtra(ItemDisplayActivity.POST_DATA_KEY,responseData);
        startActivity(intent);
    }

    @Override
    public void onOrderNowClicked(int position) {
        Toast.makeText(getActivity(),"Add function!",Toast.LENGTH_SHORT).show();
    }

    @Override
    public void onCategoryClicked(int position) {
        CategoryResponseData categoryResponseData=categoryResponseDataList.get(position);
        filterList(categoryResponseData.getCategoryName());
    }
}