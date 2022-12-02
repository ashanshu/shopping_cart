# Design Highlights
#1. Avoid duplicate submittion
Save a uniq id to session, when the uniq id is the same as previous id, don't execute the request.

#2. 2 decimal places
Round prices with 2 decimal places before displaying to users and calculating. Make sure users are not confused with the total prices.
When using number_format function, don't forget to add the 3rd and 4th parameters, otherwise the price over 1,000 will cause a bug.

#3. further discussion
The products data is hard code, for further discussion if the data comes from database or an service, we need to consider the change of product name and price. So we can compare $_SESSION["cart"] and products array to find out the deleted item in cart, then delete it from cart. And modify addCart() and removeCart() with adding param $price to deal with the price change. 