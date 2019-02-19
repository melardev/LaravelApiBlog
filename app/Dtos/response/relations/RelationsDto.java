package com.melardev.spring.twitterapi.dtos.response.relations;

import com.melardev.spring.twitterapi.dtos.PageMeta;
import com.melardev.spring.twitterapi.models.User;

import java.util.Collection;

public class RelationsDto {
    FollowingListDto following;
    FollowersListDto followers;

    public RelationsDto(FollowingListDto followingListDto, FollowersListDto followersListDto) {
        this.followers = followersListDto;
        this.following = followingListDto;
    }

    public static RelationsDto build(PageMeta pageMetaFollowing, Collection<User> following, PageMeta pageMetaFollowers, Collection<User> followers) {
        return new RelationsDto(
                FollowingListDto.build(pageMetaFollowing, following),
                FollowersListDto.build(pageMetaFollowers, followers)
        );
    }

    public FollowingListDto getFollowing() {
        return following;
    }

    public void setFollowing(FollowingListDto following) {
        this.following = following;
    }

    public FollowersListDto getFollowers() {
        return followers;
    }

    public void setFollowers(FollowersListDto followers) {
        this.followers = followers;
    }
}
