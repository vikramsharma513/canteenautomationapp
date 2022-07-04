package com.riya.canteenautomationapp.utils;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.Filter;
import android.widget.Filterable;
import android.widget.TextView;

import androidx.annotation.NonNull;
import androidx.recyclerview.widget.RecyclerView;

import com.riya.canteenautomationapp.R;
import com.riya.canteenautomationapp.responses.FoodItemResponseData;
import com.squareup.picasso.Picasso;

import java.io.Serializable;
import java.util.ArrayList;
import java.util.List;

import de.hdodenhof.circleimageview.CircleImageView;

public class FeedAdapter extends RecyclerView.Adapter<FeedAdapter.ViewHolder> implements Serializable, Filterable {
    List<FoodItemResponseData> data;
    List<FoodItemResponseData> searchData;
    Context context;
    LayoutInflater layoutInflater;
    ClickListeners clickListeners;

    public FeedAdapter(List<FoodItemResponseData> data, Context context) {
        this.data = data;
        this.context = context;
        searchData= new ArrayList<>(data);
        layoutInflater= LayoutInflater.from(context);
    }

    @NonNull
    @Override
    public FeedAdapter.ViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {

        View view= layoutInflater.inflate(R.layout.layout_feed,parent,false);
        return new ViewHolder(view);
    }

    @Override
    public void onBindViewHolder(@NonNull FeedAdapter.ViewHolder holder, int position) {
        FoodItemResponseData holderList= data.get(position);
        holder.title.setText(holderList.getFoodName());
        holder.categoryTV.setText("Category: "+holderList.getCategoryName());
        holder.price.setText("Rs: "+holderList.getPrice().toString());
        Picasso.get().load(Constants.BASE_URL+holderList.getImageUrl()).into(holder.foodPic);
    }

    @Override
    public int getItemCount() {
        return data.size();
    }
///<search>
    @Override
    public Filter getFilter() {
        return filter;
    }

    private Filter filter= new Filter() {
        @Override
        protected FilterResults performFiltering(CharSequence constraint) {
            List<FoodItemResponseData> foodItemResponseDataList= new ArrayList<>();
            if(constraint==null || constraint.length()==0){
                foodItemResponseDataList.addAll(searchData);
            }else{
                String filterPattern = constraint.toString().toLowerCase().trim();
                for (FoodItemResponseData foodItemResponseData:searchData){
                    if(foodItemResponseData.getFoodName().toLowerCase().contains(filterPattern)){
                        foodItemResponseDataList.add(foodItemResponseData);
                    }
                    else if(foodItemResponseData.getCategoryName().toLowerCase().contains(filterPattern)){
                        foodItemResponseDataList.add(foodItemResponseData);
                    }
                }
            }
            FilterResults results=new FilterResults();
            results.values= foodItemResponseDataList;
            return results;
        }

        @Override
        protected void publishResults(CharSequence constraint, FilterResults results) {
            data.clear();
            data.addAll((List) results.values);
            notifyDataSetChanged();
        }
    };

    /// </search>
    public interface ClickListeners {
        void onItemClicked(int position);
        void onOrderNowClicked(int position);
    }

    public void setClickListeners(ClickListeners clickListeners){
        this.clickListeners= clickListeners;
    }

    class ViewHolder extends RecyclerView.ViewHolder{
        TextView title, price, categoryTV;
        CircleImageView foodPic;
        Button orderBtn;

        public ViewHolder(@NonNull View itemView) {
            super(itemView);

            title= itemView.findViewById(R.id.titleTV);
            price= itemView.findViewById(R.id.priceTV);
            foodPic=itemView.findViewById(R.id.foodIV);
            categoryTV=itemView.findViewById(R.id.categoryName);
            orderBtn=itemView.findViewById(R.id.Btnorder);

            foodPic.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View v) {

                    clickListeners.onItemClicked(getAdapterPosition());
                }
            });

            orderBtn.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View v) {
                    clickListeners.onOrderNowClicked(getAdapterPosition());
                }
            });

        }
    }
}
