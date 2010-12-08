class CreateBookStores < ActiveRecord::Migration
  def self.up
    create_table :book_stores do |t|

      t.timestamps
    end
  end

  def self.down
    drop_table :book_stores
  end
end
